<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderPaymentStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\VoucherStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public string $ControllerName = 'Trang chủ';

    public function __construct()
    {
        $pageTitle = Route::currentRouteAction();
        $pageTitle = explode('@', $pageTitle)[1];
        view()->share('ControllerName', $this->ControllerName);
        view()->share('pageTitle', $pageTitle);

    }

    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $customerCount = Customer::query()->count();
        $orderCount = Order::query()->where('payment_status',
            OrderPaymentStatusEnum::DA_THANH_TOAN)
            ->whereMonth('created_at', $currentMonth)->count();
        $productCount = Product::query()->count();
        $serviceCount = Service::query()->count();
        $voucherCount = Voucher::query()->where('status', VoucherStatusEnum::HOAT_DONG)->count();
        $appointmentCount = Appointment::query()->whereMonth('created_at', $currentMonth)->count();

        $order_revenue = Order::query()->where('payment_status',
            OrderPaymentStatusEnum::DA_THANH_TOAN)
            ->whereMonth('created_at', $currentMonth)->sum('total');

        $appointment_revenue = Appointment::query()->whereMonth('date', $currentMonth)->sum('total_price');
        $revenue = $order_revenue + $appointment_revenue;

        $top_product = Product::query()->orderBy('sold', 'desc')->take(10)->get();

        return view('admin.layouts.home', [
            'customerCount' => $customerCount,
            'orderCount' => $orderCount,
            'productCount' => $productCount,
            'serviceCount' => $serviceCount,
            'voucherCount' => $voucherCount,
            'appointmentCount' => $appointmentCount,
            'revenue' => $revenue,
            'top_product' => $top_product,
        ]);
    }

    public function statistic()
    {
        return view('admin.statistic');
    }

    public function get_revenue(Request $request)
    {
        $max_date = $request->get('days');
        $arr = [];
        if ($max_date == -1) {
            $results = Order::query()
                ->selectRaw('DATE_FORMAT(created_at, "%m") as date, sum(total) as income')
                ->where('status', OrderStatusEnum::HOAN_THANH)
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%m")'))
                ->get();
            $today = Carbon::now()->format('m');

            for ($i = 1; $i <= $today; $i++) {
                $key = ($i < 10) ? "0".$i : $i;
                $arr[$key] = 0;
            }

            foreach ($results as $each) {
                $arr[$each->date] = (float) $each->income;
            }
        } elseif ($max_date == -2) {
            $results = Order::query()
                ->selectRaw('DATE_FORMAT(created_at, "%Y") as date, sum(total) as income')
                ->where('status', OrderStatusEnum::HOAN_THANH)
                ->whereRaw('DATE(created_at) >= CURDATE() - INTERVAL 10 YEAR')
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y")'))
                ->get();

            $today = now()->format('Y');
            $start_year = now()->subYears(10)->format('Y');

            for ($i = $start_year; $i <= $today; $i++) {
                $arr[$i] = 0;
            }

            foreach ($results as $each) {
                $arr[$each->date] = (float) $each->income;
            }
        } else {
//            \DB::enableQueryLog();
            $results = Order::query()
                ->selectRaw('DATE_FORMAT(created_at, "%e-%m") as date, sum(total) as income')
                ->where('status', OrderStatusEnum::HOAN_THANH)
                ->whereRaw('DATE(created_at) >= CURDATE() - INTERVAL ? DAY', [$max_date])
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%e-%m")'))
                ->get();
//            dd(\DB::getQueryLog());

            $today = now()->format('d');

            if ($today < $max_date) {
                $days_last_month = $max_date - $today;
                $last_month = now()->subMonth()->format('m');
                $max_day_last_month = now()->subMonth()->daysInMonth;
                $start_date_last_month = $max_day_last_month - $days_last_month;

                for ($i = $start_date_last_month; $i <= $max_day_last_month; $i++) {
                    $key = $i."-".$last_month;
                    $arr[$key] = 0;
                }

                $start_date_current_month = 1;
            } elseif ($today == $max_date) {
                $start_date_current_month = 1;
            } else {
                $start_date_current_month = $today - $max_date;
            }

            $current_month = now()->format('m');

            for ($i = $start_date_current_month; $i <= $today; $i++) {
                $key = $i."-".$current_month;
                $arr[$key] = 0;
            }

            foreach ($results as $each) {
                $arr[$each->date] = (float) $each->income;
            }
        }

        return response()->json($arr);
    }
}
