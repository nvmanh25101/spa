<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public string $ControllerName = 'Giỏ hàng';

    public function __construct()
    {
        view()->share('ControllerName', $this->ControllerName);
    }

    public function index()
    {
        $cart = Cart::query()->where('customer_id', auth()->id())->first();

        return view('customer.cart', [
            'cart' => $cart,
        ]);
    }

    public function store(Request $request)
    {
        $cart = Cart::query()->where('customer_id', auth()->id())->first();
        if (!$cart) {
            $cart = Cart::query()->create([
                'customer_id' => auth()->id()
            ]);
        }
        $quantity = $request->get('quantity');
        $product_id = $request->get('product_id');
        $inventory = Product::query()->findOrFail($product_id)->quantity;
        if ($quantity > $inventory) {
            return redirect()->back()->with([
                'error' => 'Số lượng sản phẩm trong kho không đủ'
            ]);
        }

        $cart->products()->attach($request->get('product_id'), [
            'quantity' => $request->get('quantity')
        ]);

        return redirect()->route('cart.index');
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::query()->findOrFail($id);
        $quantity = $request->get('quantity');
        $product_id = $request->get('product_id');
        $inventory = Product::query()->findOrFail($product_id)->quantity;
        if ($quantity > $inventory) {
            return response()->json([
                'error' => 'Số lượng sản phẩm trong kho không đủ'
            ]);
        }

        $cart->products()->updateExistingPivot($request->get('product_id'), [
            'quantity' => $request->get('quantity')
        ]);

        return redirect()->route('cart.index');
    }

    public function destroy(Request $request, $id)
    {
        $cart = Cart::query()->findOrFail($id);

        $cart->products()->detach($request->get('product_id'));

        return redirect()->route('cart.index');
    }
}
