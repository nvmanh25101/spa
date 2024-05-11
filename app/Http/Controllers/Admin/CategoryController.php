<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Category\StatusEnum;
use App\Enums\Category\TypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Category;
use App\Models\Notification;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public string $ControllerName = 'Danh mục';

    public function __construct()
    {
        $pageTitle = Route::currentRouteAction();
        $pageTitle = explode('@', $pageTitle)[1];
        view()->share('ControllerName', $this->ControllerName);
        view()->share('pageTitle', $pageTitle);

        $arrCategoryType = TypeEnum::getArrayView();
        view()->share('arrCategoryType', $arrCategoryType);

        $arrCategoryStatus = StatusEnum::getArrayView();
        view()->share('arrCategoryStatus', $arrCategoryStatus);

        $arrNoti = Notification::query()->get();
        view()->share('arrNoti', $arrNoti);
    }

    public function index()
    {
        return view('admin.categories.index');
    }

    public function api()
    {
        return DataTables::of(Category::query())
            ->editColumn('type', function ($object) {
                return TypeEnum::getKeyByValue($object->type);
            })
            ->editColumn('status', function ($object) {
                return StatusEnum::getKeyByValue($object->status);
            })
            ->addColumn('edit', function ($object) {
                return route('admin.categories.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('admin.categories.destroy', $object);
            })
            ->filterColumn('status', function ($query, $keyword) {
                if ($keyword !== '-1') {
                    $query->where('status', $keyword);
                }
            })
            ->filterColumn('type', function ($query, $keyword) {
                if ($keyword !== '-1') {
                    $query->where('type', $keyword);
                }
            })
            ->make(true);
    }

    public function store(StoreRequest $request)
    {
        if (Category::query()->create($request->validated())) {
            return redirect()->route('admin.categories.index')->with(['success' => 'Thêm mới thành công']);
        }
        return redirect()->back()->withErrors('message', 'Thêm mới thất bại');
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function show(Category $category)
    {
        //
    }

    public function edit($categoryId)
    {
        $category = Category::query()->findOrFail($categoryId);

        return view(
            'admin.categories.edit',
            [
                'category' => $category,
            ]
        );
    }

    public function update(UpdateRequest $request, $categoryId)
    {
        $category = Category::query()->findOrFail($categoryId);
        $category->fill($request->validated());
        if ($category->save()) {
            return redirect()->route('admin.categories.index')->with(['success' => 'Cập nhật thành công']);
        }
        return redirect()->back()->withErrors('message', 'Cập nhật thất bại');
    }

    public function destroy($categoryId)
    {
        if (Product::query()->where('category_id', $categoryId)->exists()) {
            return response()->json([
                'error' => 'Không thể xóa danh mục này vì có sản phẩm thuộc danh mục này',
            ]);
        }

        Category::destroy($categoryId);

        return response()->json([
            'success' => 'Xóa thành công',
        ]);
    }
}
