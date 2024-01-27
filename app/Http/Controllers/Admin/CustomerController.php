<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public string $ControllerName = 'Khách hàng';

    public function __construct()
    {
        $pageTitle = Route::currentRouteAction();
        $pageTitle = explode('@', $pageTitle)[1];
        view()->share('ControllerName', $this->ControllerName);
        view()->share('pageTitle', $pageTitle);
    }

    public function index()
    {
        return view('admin.customers.index');
    }

    public function api()
    {
        return DataTables::of(Customer::query())
            ->addColumn('revenue', function ($object) {
                return $object->revenue;
            })
            ->addColumn('show', function ($object) {
                return route('admin.customers.show', $object);
            })
            ->make(true);
    }

    public function show($employeeId)
    {
        $customer = Customer::query()->findOrFail($employeeId);

        return view(
            'admin.customers.show',
            [
                'customer' => $customer,
            ]
        );
    }
}
