<?php

namespace App\Http\Controllers\Customer;

use App\Enums\OrderPaymentEnum;
use App\Enums\OrderPaymentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class VnpayController extends Controller
{
    public function create($order)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $vnp_Version = "2.1.0";
        $vnp_ReturnUrl = route('vnpay.return');
        $vnp_Url = env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');

        $vnp_TmnCode = env('VNPAY_TMNCODE', 'UA9Z9GG3'); //Mã website tại VNPAY
        $vnp_HashSecret = env('VNPAY_HASHSECRET', 'OTLBXSDGCEGKPDYVLGDUHWYJSENPCCLY'); //Chuỗi bí mật

        $vnp_TxnRef = $order->code;
        $vnp_OrderInfo = "Thanh toán đơn hàng có mã ".$vnp_TxnRef;
        $vnp_OrderType = "billpayment";
        $vnp_Amount = $order->total * 100;
        $vnp_Locale = "vn";
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        $vnp_Command = "pay";
        $create_date = date('YmdHis');
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($create_date)));

        $inputData = array(
            "vnp_Version" => $vnp_Version,
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => $vnp_Command,
            "vnp_CreateDate" => $create_date,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $expire
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&'.urlencode($key)."=".urlencode($value);
            } else {
                $hashdata .= urlencode($key)."=".urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key)."=".urlencode($value).'&';
        }

        $vnp_Url = $vnp_Url."?".$query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
            $vnp_Url .= 'vnp_SecureHash='.$vnpSecureHash;
        }
        header('Location: '.$vnp_Url);
//        return redirect()->away($vnp_Url);
    }

    public function return(Request $request)
    {
        if ($request->query('vnp_TransactionStatus') == 00) {

            $order_code = request('vnp_TxnRef');
            $order = Order::where('code', $order_code)->first();
            $order->update([
                'payment_method' => OrderPaymentEnum::CHUYEN_KHOAN,
                'payment_status' => OrderPaymentStatusEnum::DA_THANH_TOAN,
            ]);

            return view('customer.thankyou');
        }
        return redirect()->route('orders.index')->with(['error' => 'Thanh toán đơn hàng không thành công! Vui lòng thử lại!']);
    }
}
