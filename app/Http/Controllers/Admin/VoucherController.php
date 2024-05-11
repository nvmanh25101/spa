<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VoucherApplyTypeEnum;
use App\Enums\VoucherStatusEnum;
use App\Enums\VoucherTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Voucher\StoreRequest;
use App\Http\Requests\Admin\Voucher\UpdateRequest;
use App\Models\Notification;
use App\Models\Voucher;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class VoucherController extends Controller
{
    public string $ControllerName = 'Voucher';

    public function __construct()
    {
        $pageTitle = Route::currentRouteAction();
        $pageTitle = explode('@', $pageTitle)[1];
        view()->share('ControllerName', $this->ControllerName);
        view()->share('pageTitle', $pageTitle);

        $arrVoucherType = VoucherTypeEnum::getArrayView();
        view()->share('arrVoucherType', $arrVoucherType);

        $arrVoucherApplyType = VoucherApplyTypeEnum::getArrayView();
        view()->share('arrVoucherApplyType', $arrVoucherApplyType);

        $arrVoucherStatus = VoucherStatusEnum::getArrayView();
        view()->share('arrVoucherStatus', $arrVoucherStatus);

        $arrNoti = Notification::query()->get();
        view()->share('arrNoti', $arrNoti);
    }

    public function index()
    {
        return view('admin.vouchers.index');
    }

    public function api()
    {
        return DataTables::of(Voucher::query())
            ->editColumn('applicable_type', function ($object) {
                return VoucherApplyTypeEnum::getKeyByValue($object->applicable_type);
            })
            ->editColumn('value', function ($object) {
                return number_format($object->value).($object->type === VoucherTypeEnum::PHAN_TRAM ? '%' : ' VNĐ');
            })
            ->editColumn('start_date', function ($object) {
                return $object->start_date_display;
            })
            ->editColumn('end_date', function ($object) {
                return $object->end_date_display;
            })
            ->editColumn('status', function ($object) {
                return VoucherStatusEnum::getKeyByValue($object->status);
            })
            ->addColumn('edit', function ($object) {
                return route('admin.vouchers.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('admin.vouchers.destroy', $object);
            })
            ->filterColumn('status', function ($query, $keyword) {
                if ($keyword !== '-1') {
                    $query->where('status', $keyword);
                }
            })
            ->filterColumn('applicable_type', function ($query, $keyword) {
                if ($keyword !== '-1') {
                    $query->where('type', $keyword);
                }
            })
            ->make(true);
    }

    public function store(StoreRequest $request)
    {
        if (Voucher::query()->create($request->validated())) {
            return redirect()->route('admin.vouchers.index')->with(['success' => 'Thêm mới thành công']);
        }
        return redirect()->back()->withErrors('message', 'Thêm mới thất bại');
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function edit($voucherId)
    {
        $voucher = Voucher::query()->findOrFail($voucherId);

        return view(
            'admin.vouchers.edit',
            [
                'voucher' => $voucher,
            ]
        );
    }

    public function update(UpdateRequest $request, $voucherId)
    {
        $cvoucher = Voucher::query()->findOrFail($voucherId);
        $cvoucher->fill($request->validated());
        if ($cvoucher->save()) {
            return redirect()->route('admin.vouchers.index')->with(['success' => 'Cập nhật thành công']);
        }
        return redirect()->back()->withErrors('message', 'Cập nhật thất bại');
    }

    public function destroy($voucherId)
    {
        if (Voucher::destroy($voucherId)) {
            return response()->json([
                'success' => 'Xóa thành công',
            ]);
        }

        return response()->json([
            'error' => 'Xóa thất bại',
        ]);
    }
}
