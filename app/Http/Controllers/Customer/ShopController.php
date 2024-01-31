<?php

namespace App\Http\Controllers\Customer;

use App\Enums\Category\StatusEnum;
use App\Enums\Category\TypeEnum;
use App\Enums\TourStatusEnum;
use App\Enums\ServiceStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ReviewRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Reservation;
use App\Models\Tour;
use App\Models\Service;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public string $ControllerName = 'Trang chủ';

    public function __construct()
    {
        view()->share('ControllerName', $this->ControllerName);

        $categories = Category::query()->where('status', '=', StatusEnum::HOAT_DONG)->get(['id', 'name']);
        view()->share('categories', $categories);
    }

    public function index(Request $request)
    {
        $category_filter = $request->query('category');
        $destination_filter = $request->query('destination');
        $categories = Category::query()->where('status', '=', StatusEnum::HOAT_DONG)
            ->get(['id', 'name']);

        if ($category_filter) {
            $category = Category::query()->where('id', $request->query('category'))->get();
            $tours = Tour::query()->whereBelongsTo($category)->where('status', '=',
                TourStatusEnum::HOAT_DONG)->simplePaginate(12);
        } elseif ($destination_filter) {
            $tours = Tour::query()->whereHas('destinations', function ($query) use ($destination_filter) {
                $query->where('name', 'like', '%' . $destination_filter . '%');
            })->where('status', '=', TourStatusEnum::HOAT_DONG)->simplePaginate(12);
        } else {
            $tours = Tour::query()->where('status', '=',
                TourStatusEnum::HOAT_DONG)->simplePaginate(12);
        }
        return view('customer.home', [
            'tours' => $tours,
            'categories' => $categories,
            'category_filter' => $category_filter
        ]);
    }
    public function tour(Request $request, $id)
    {
        $tour = Tour::query()->with(['schedules', 'services', 'destinations'])->findOrFail($id);
        $reviews = $tour->reviews()->with('customer')->simplePaginate(5);
        $customer = auth()->user();
        if ($customer) {
            $order_count = Reservation::whereHas('tour', function ($query) use ($id) {
                $query->where('tours.id', $id);
            })->where('customer_id', $customer->id)->count();
        } else {
            $order_count = 0;
        }

        return view('customer.tour', [
            'tour' => $tour,
            'reviews' => $reviews,
            'order_count' => $order_count
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
        $tour = Tour::query()->findOrFail($id);
        $tour->reviews()->create([
            'rating' => $request->validated('rating'),
            'content' => $request->validated('content'),
            'customer_id' => auth()->user()->id
        ]);

        return redirect()->route('customers.tour', $tour)->with('success', 'Đánh giá tour thành công');
    }
}
