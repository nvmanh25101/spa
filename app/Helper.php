<?php

use App\Enums\VoucherTypeEnum;
use App\Models\Notification;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getDurationPrice')) {
    function getDurationPrice($request)
    {
        $durations = $request->validated()['duration'];
        $prices = $request->validated()['price'];
        unset($request->validated()['duration'], $request->validated()['price']);
        if (count($durations) !== count($prices)) {
            return redirect()->back()->withErrors('message', 'Kiểm tra thời gian và giá');
        }

        return array_map(function ($duration, $price) {
            return [
                "duration" => $duration,
                "price" => $price,
            ];
        }, $durations, $prices);
    }
}

if (!function_exists('checkVoucher')) {
    function checkVoucher($request, $model, $applicable_type, $price)
    {
        if ($request->validated()['voucher_id']) {
            $voucher = Voucher::query()->find($request->validated()['voucher_id']);
            if (!Auth::guard('customer')->check()) {
                return 'Bạn cần đăng nhập để sử dụng voucher';
            }

            $count = $model::query()->where('customer_id', Auth::guard('customer')->user()->id)
                ->where('voucher_id', $voucher->id)
                ->count();
            if ($count > $voucher->uses_per_customer) {
                return 'Bạn đã sử dụng hết lượt sử dụng voucher';
            }

            if ($voucher->applicable_type !== $applicable_type) {
                return 'Voucher không hợp lệ';
            }

            if ($voucher->uses_per_voucher < 1) {
                return 'Voucher đã hết lượt sử dụng';
            }

            if ($voucher->type === VoucherTypeEnum::PHAN_TRAM) {
                $discount = $price * $voucher->value / 100;
                if ($discount > $voucher->max_spend) {
                    $discount = $voucher->max_spend;
                }

                if ($discount > $price) {
                    $discount = $price;
                }

                $total = $price - $discount;
            } else {
                $voucher_value = $voucher->value;
                if ($voucher_value > $price) {
                    $voucher_value = $price;
                }
                $total = $price - $voucher_value;
            }

            return (float) $total;
        }

        return (float) $price;
    }
}

if (!function_exists('deleteNoti')) {
    function deleteNoti($id, $type)
    {
        $noti = Notification::query()->where('object_id', $id)->where('type', $type)->first();
        $noti?->delete();
    }
}
