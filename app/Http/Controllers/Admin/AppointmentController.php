<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminType;
use App\Enums\AppointmentStatusEnum;
use App\Enums\NotiType;
use App\Enums\VoucherApplyTypeEnum;
use App\Enums\VoucherStatusEnum;
use App\Enums\VoucherTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Appointment\UpdateRequest;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Time;
use App\Models\Voucher;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class AppointmentController extends Controller
{
    public string $ControllerName = 'Lịch đặt';

    public function __construct()
    {
        $pageTitle = Route::currentRouteAction();
        $pageTitle = explode('@', $pageTitle)[1];
        view()->share('ControllerName', $this->ControllerName);
        view()->share('pageTitle', $pageTitle);

        $arrAppointmentStatus = AppointmentStatusEnum::getArrayView();
        view()->share('arrAppointmentStatus', $arrAppointmentStatus);

        $arrNoti = Notification::query()->get();
        view()->share('arrNoti', $arrNoti);
    }

    public function index()
    {
        return view('admin.appointments.index');
    }

    public function api()
    {
        return DataTables::of(Appointment::query())
            ->addColumn('service_name', function ($object) {
                return $object->service->name;
            })
            ->addColumn('datetime', function ($object) {
                return $object->time->time_display.' - '.$object->date_display;
            })
            ->editColumn('status', function ($object) {
                return AppointmentStatusEnum::getKeyByValue($object->status);
            })
            ->addColumn('edit', function ($object) {
                return route('admin.appointments.edit', $object);
            })
            ->filterColumn('status', function ($query, $keyword) {
                if ($keyword !== '-1') {
                    $query->where('status', $keyword);
                }
            })
            ->make(true);
    }

    public function edit($appointmentId)
    {
        deleteNoti($appointmentId, NotiType::LICH);

        $employees = Admin::query()->where('role', '=', AdminType::DICH_VU)
            ->get(['id', 'name']);
        $appointment = Appointment::query()->with('service', 'service.category')->findOrFail($appointmentId);
        $times = Time::query()->get();

        $vouchers = Voucher::query()->where('status', '=', VoucherStatusEnum::HOAT_DONG)
            ->where('applicable_type', VoucherApplyTypeEnum::DICH_VU)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('uses_per_voucher', '>', 0)
            ->get();

        return view(
            'admin.appointments.edit',
            [
                'appointment' => $appointment,
                'employees' => $employees,
                'times' => $times,
                'vouchers' => $vouchers,
            ]
        );
    }

    public function update(UpdateRequest $request, $appointmentId)
    {
        $appointment = Appointment::query()->findOrFail($appointmentId);
        $arr = $request->validated();
        $price = $appointment->price;

        $voucher = Voucher::query()->find($arr['voucher_id']);
        if ($voucher) {
            $count = Appointment::query()->where('customer_id', $appointment->customer_id)
                ->where('voucher_id', $voucher->id)
                ->count();
            if ($count > $voucher->uses_per_customer) {
                return redirect()->back()->with(['error' => 'Khách hàng đã sử dụng hết lượt sử dụng voucher']);
            }

            if ($voucher->applicable_type !== VoucherApplyTypeEnum::DICH_VU) {
                return redirect()->back()->with(['error' => 'Voucher không hợp lệ']);
            }

            if ($voucher->uses_per_voucher < 1) {
                return redirect()->back()->with(['error' => 'Voucher đã hết lượt sử dụng']);
            }

            if ($voucher->type === VoucherTypeEnum::PHAN_TRAM) {
                $discount = $price * $voucher->value / 100;
                if ($discount > $voucher->max_spend) {
                    $discount = $voucher->max_spend;
                }

                if ($discount > $price) {
                    $discount = $price;
                }

                $arr['total_price'] = $price - $discount;
            } else {
                $voucher_value = $voucher->value;
                if ($voucher_value > $price) {
                    $voucher_value = $price;
                }
                $arr['total_price'] = $price - $voucher_value;
            }
        } else {
            $arr['total_price'] = $price;
        }

        if (is_string($arr['total_price'])) {
            return redirect()->back()->with(['error' => $arr['total_price']]);
        }
        $voucher = Voucher::query()->find($request->validated()['voucher_id']);
        $appointment->fill($arr);

        if ($appointment->save()) {
            --$voucher->uses_per_voucher;
            $voucher->save();
            return redirect()->route('admin.appointments.index')->with(['success' => 'Cập nhật thành công']);
        }
        return redirect()->back()->withErrors('message', 'Cập nhật thất bại');
    }

}
