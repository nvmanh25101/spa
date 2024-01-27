<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AccountRequest;
use App\Models\Customer;

class AccountController extends Controller
{
    public string $ControllerName = 'Tài khoản';

    public function __construct()
    {
        view()->share('ControllerName', $this->ControllerName);
    }

    public function edit($id)
    {
        $account = Customer::query()->with(['orders', 'appointments'])->findOrFail($id);
        $orders = $account->orders()->orderByDesc('id')->paginate(15);
        $reservations = $account->appointments()->orderByDesc('id')->paginate(5);

        return view('customer.account', [
            'account' => $account,
            'orders' => $orders,
            'reservations' => $reservations
        ]);
    }

    public function update(AccountRequest $request, $id)
    {
        $account = Customer::query()->findOrFail($id);
        $account->fill($request->validated());
        $account->save();

        return redirect()->route('account.edit', $account)->with('success', 'Cập nhật tài khoản thành công');
    }
}
