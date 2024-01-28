<?php

use App\Enums\VoucherTypeEnum;
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
        $error = '';
        if ($request->validated()['voucher_id']) {
            $voucher = Voucher::query()->find($request->validated()['voucher_id']);
            if (!Auth::guard('customer')->check()) {
                $error = 'Bạn cần đăng nhập để sử dụng voucher';
                return $error;
            }

            $count = $model::query()->where('customer_id', Auth::guard('customer')->user()->id)
                ->where('voucher_id', $voucher->id)
                ->count();
            if ($count > $voucher->uses_per_customer) {
                $error = 'Bạn đã sử dụng hết lượt sử dụng voucher';
                return $error;
            }

            if ($voucher->applicable_type !== $applicable_type) {
                $error = 'Voucher không hợp lệ';
                return $error;
            }

            if ($voucher->uses_per_voucher < 1) {
                $error = 'Voucher đã hết lượt sử dụng';
                return $error;
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

            --$voucher->uses_per_voucher;
            $voucher->save();

            return $total;
        }
        
        return $price;
    }
}
