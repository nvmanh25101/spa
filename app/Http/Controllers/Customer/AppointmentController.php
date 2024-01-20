<?php

namespace App\Http\Controllers\Customer;

use App\Enums\Category\StatusEnum;
use App\Enums\Category\TypeEnum;
use App\Enums\ServiceStatusEnum;
use App\Enums\VoucherApplyTypeEnum;
use App\Enums\VoucherStatusEnum;
use App\Enums\VoucherTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Appointment\StoreRequest;
use App\Models\Appointment;
use App\Models\Category;
use App\Models\PriceService;
use App\Models\Service;
use App\Models\Time;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $arr['total_price'] = $price;
        $arr['customer_id'] = Auth::guard('customer')->user()->id;

        $appointment = Appointment::query()->where('customer_id', Auth::guard('customer')->user()->id)
            ->whereDate('date', $date)
            ->where('time_id', $request->validated()['time_id'])
            ->first();
        if ($appointment) {
            return redirect()->back()->with('error', 'Bạn đã đặt lịch vào thời gian này');
        }

        if ($request->validated()['voucher_id']) {
            $voucher = Voucher::query()->find($request->validated()['voucher_id']);
            if (!Auth::guard('customer')->check()) {
                return redirect()->back()->with('error', 'Bạn cần đăng nhập để sử dụng voucher');
            }

            $count = Appointment::query()->where('customer_id', Auth::guard('customer')->user()->id)
                ->where('voucher_id', $voucher->id)
                ->count();
            if ($count > $voucher->uses_per_customer) {
                return redirect()->back()->with('error', 'Bạn đã sử dụng hết lượt sử dụng voucher');
            }

            if ($voucher->applicable_type !== VoucherApplyTypeEnum::DICH_VU) {
                return redirect()->back()->with('error', 'Voucher không hợp lệ');
            }

            if ($voucher->uses_per_voucher < 1) {
                return redirect()->back()->with('error', 'Voucher đã hết lượt sử dụng');
            }

            if ($voucher->type === VoucherTypeEnum::PHAN_TRAM) {
                $arr['total_price'] = $price - $price * $voucher->value / 100;
            } else {
                $arr['total_price'] = $price - $voucher->value;
            }
            --$voucher->uses_per_voucher;
            $voucher->save();
        }

        if (Appointment::query()->create($arr)) {
            return redirect()->back()->with('success', 'Đặt lịch thành công');
        }
        return redirect()->back()->with('error', 'Đặt lịch thất bại');

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

}
