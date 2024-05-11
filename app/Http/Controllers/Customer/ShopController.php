<?php

namespace App\Http\Controllers\Customer;

use App\Enums\Category\StatusEnum;
use App\Enums\Category\TypeEnum;
use App\Enums\ProductStatusEnum;
use App\Enums\ServiceStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReviewRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public string $ControllerName = 'Trang chủ';

    public function __construct()
    {
        view()->share('ControllerName', $this->ControllerName);
    }

    public function index()
    {
        return view('customer.home');
    }

    public function services(Request $request)
    {
        $categories = Category::query()->where('status', '=', StatusEnum::HOAT_DONG)
            ->where('type', '=', TypeEnum::DICH_VU)
            ->get(['id', 'name']);

        if ($request->query('category')) {
            $category = Category::query()->where('id', $request->query('category'))->get();
        } else {
            $category = $categories->first();
        }
        if ($category) {

            $services = Service::query()->with('priceServices')->whereBelongsTo($category)->where('status', '=',
                ServiceStatusEnum::HOAT_DONG)->get();
        } else {
            $services = [];

        }

        return view('customer.services', [
            'categories' => $categories,
            'services' => $services
        ]);
    }

    public function products(Request $request)
    {
        $keyword = '';

        if ($request->query('q')) {
            $keyword = $request->query('q');
        }

        $category_filter = $request->query('category');
        $categories = Category::query()->where('status', '=', StatusEnum::HOAT_DONG)
            ->where('type', '=', TypeEnum::SAN_PHAM)
            ->get(['id', 'name']);

        $productsQuery = Product::query()
            ->where('status', '=', ProductStatusEnum::HOAT_DONG)
            ->where('name', 'like', '%'.$keyword.'%');

        if ($category_filter) {
            $productsQuery->whereHas('category', function ($query) use ($category_filter) {
                $query->where('id', $category_filter);
            });
        }

        $products = $productsQuery->simplePaginate(12);
//        if ($category_filter) {
//            $category = Category::query()->where('id', $category_filter)->get();
//            $products = Product::query()->whereBelongsTo($category)->where('name', 'like',
//                '%'.$keyword.'%')->where('status', '=',
//                ProductStatusEnum::HOAT_DONG)->simplePaginate(12);
//        } else {
//            $products = Product::query()->where('name', 'like', '%'.$keyword.'%')->where('status', '=',
//                ProductStatusEnum::HOAT_DONG)->simplePaginate(12);
//        }

        return view('customer.products', [
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function product(Request $request, $id)
    {
        $product = Product::query()->findOrFail($id);
        $reviews = $product->reviews()->with('customer')->simplePaginate(5);

        if (auth()->user()) {
            $order_count = Order::whereHas('products', function ($query) use ($id) {
                $query->where('products.id', $id);
            })->where('customer_id', auth()->user()->id)->count();
        }

        return view('customer.product', [
            'product' => $product,
            'reviews' => $reviews,
            'order_count' => $order_count ?? 0
        ]);
    }

    public function blogs()
    {
        $blogs = Blog::query()->simplePaginate(10);

        return view('customer.blogs', [
            'blogs' => $blogs
        ]);
    }

    public function blog(Request $request, $id)
    {
        $blog = Blog::query()->findOrFail($id);

        return view('customer.blog', [
            'blog' => $blog,
        ]);
    }

    public function review(ReviewRequest $request, $id)
    {
        $product = Product::query()->findOrFail($id);
        $product->reviews()->create([
            'rating' => $request->validated('rating'),
            'content' => $request->validated('content'),
            'customer_id' => auth()->user()->id
        ]);

        return redirect()->route('customers.product', $product)->with('success', 'Đánh giá sản phẩm thành công');
    }

    public function search(Request $request)
    {
        $keyword = $request->query('q');
        $products = Product::query()->where('name', 'like', '%'.$keyword.'%')->where('status', '=',
            ProductStatusEnum::HOAT_DONG)->get();

        $arr = [];
        foreach ($products as $product) {
            $arr[] = [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'price' => $product->price,
                'url' => route('customers.product', $product)
            ];
        }

        return response()->json($arr);
    }
}
