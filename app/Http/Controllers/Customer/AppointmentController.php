<?php

namespace App\Http\Controllers\Customer;

use App\Enums\AppointmentStatusEnum;
use App\Enums\Category\StatusEnum;
use App\Enums\Category\TypeEnum;
use App\Enums\ServiceStatusEnum;
use App\Enums\VoucherApplyTypeEnum;
use App\Enums\VoucherStatusEnum;
use App\Events\NewAppointmentReceived;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Reservation\StoreRequest;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\PriceService;
use App\Models\Service;
use App\Models\Time;
use App\Models\Voucher;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public string $ControllerName = 'Đặt lịch';

    public function __construct()
    {
        view()->share('ControllerName', $this->ControllerName);
    }

    public function getServices(Request $request)
    {
        $category = Category::query()->where('id', $request->query('category'))->get();

        $services = Service::query()->whereBelongsTo($category)->where('status', '=',
            ServiceStatusEnum::HOAT_DONG)->get(['id', 'name']);

        return response()->json([
            'services' => $services
        ]);
    }

    public function getPrices(Request $request)
    {
        $prices = PriceService::query()->where('service_id', $request->query('service'))->get();

        return response()->json([
            'prices' => $prices
        ]);
    }

    public function show($id)
    {
        $appointment = Appointment::query()->with(['service', 'time', 'voucher', 'admin'])->find($id);

        return view('customer.appointment', [
            'appointment' => $appointment
        ]);
    }

    public function store(StoreRequest $request)
    {
        $duration_price = PriceService::query()->find($request->validated()['price_id']);
        unset($request->validated()['price_id']);
        $duration = $duration_price->duration;
        $price = $duration_price->price;
        $date = Carbon::createFromFormat('d-m-Y', $request->validated()['date'])->format('Y-m-d');
        $arr = $request->validated();
        $arr['date'] = $date;
        $arr['duration'] = $duration;
        $arr['price'] = $price;
        if (Auth::guard('customer')->check()) {
            $arr['customer_id'] = Auth::guard('customer')->user()->id ?? null;

            $appointment = Appointment::query()->where('customer_id', Auth::guard('customer')->user()->id)
                ->whereDate('date', $date)
                ->where('time_id', $request->validated()['time_id'])
                ->first();
            if ($appointment) {
                return redirect()->back()->with('error', 'Bạn đã đặt lịch vào thời gian này');
            }
        }

        $arr['total_price'] = checkVoucher($request, Appointment::class, VoucherApplyTypeEnum::DICH_VU,
            $price) ?? $price;

        if (is_string($arr['total_price'])) {
            return redirect()->back()->with(['error' => $arr['total_price']]);
        }
        $voucher = Voucher::query()->find($request->validated()['voucher_id']);
        DB::beginTransaction();
        try {
            $appointment = Appointment::query()->create($arr);

            if ($voucher) {
                --$voucher->uses_per_voucher;
                $voucher->save();
            }
            event(new NewAppointmentReceived($appointment));

            DB::commit();

            return redirect()->back()->with('success', 'Đặt lịch thành công');
        } catch (Exception $e) {
            DB::rollBack();
            // dd($e);
            return redirect()->back()->with('error', 'Đặt lịch thất bại');
        }
    }

    public function create(Request $request)
    {
        $categories = Category::query()->where('status', '=', StatusEnum::HOAT_DONG)
            ->where('type', '=', TypeEnum::DICH_VU)
            ->get(['id', 'name']);

        if ($request->query('service')) {
            $service = Service::query()->with(['category', 'priceServices'])->find($request->query('service'));
            $services = Service::query()->whereBelongsTo($service->category)->where('status', '=',
                ServiceStatusEnum::HOAT_DONG)->get(['id', 'name']);
        }

        $times = Time::all();

        $vouchers = Voucher::query()->where('status', '=', VoucherStatusEnum::HOAT_DONG)
            ->where('applicable_type', VoucherApplyTypeEnum::DICH_VU)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('uses_per_voucher', '>', 0)
            ->get();

        return view('customer.booking', [
            'times' => $times,
            'categories' => $categories,
            'service' => $service ?? null,
            'services' => $services ?? null,
            'vouchers' => $vouchers ?? null
        ]);
    }

    public function destroy($id)
    {
        $appointment = Appointment::query()->findOrFail($id);
        $appointment->update([
            'status' => AppointmentStatusEnum::KHACH_HANG_HUY
        ]);

        return response()->json([
            'success' => 'Hủy thành công',
        ]);
    }

}
