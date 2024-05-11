<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blog\StoreRequest;
use App\Http\Requests\Admin\Blog\UpdateRequest;
use App\Models\Blog;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
{
    public string $ControllerName = 'Blog';

    public function __construct()
    {
        $pageTitle = Route::currentRouteAction();
        $pageTitle = explode('@', $pageTitle)[1];
        view()->share('ControllerName', $this->ControllerName);
        view()->share('pageTitle', $pageTitle);

        $arrNoti = Notification::query()->get();
        view()->share('arrNoti', $arrNoti);
    }

    public function index()
    {
        return view('admin.blogs.index');
    }

    public function api()
    {
        return DataTables::of(Blog::query())
            ->addColumn('created_date', function ($object) {
                return $object->created_date;
            })
            ->addColumn('updated_date', function ($object) {
                return $object->updated_date;
            })
            ->addColumn('admin', function ($object) {
                return $object->admin->name;
            })
            ->addColumn('edit', function ($object) {
                return route('admin.blogs.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('admin.blogs.destroy', $object);
            })
            ->make(true);
    }

    public function store(StoreRequest $request)
    {
        $path = Storage::disk('public')->putFile('blogs', $request->file('image'));
        $arr = $request->validated();
        $arr['image'] = $path;
        $arr['admin_id'] = Auth::guard('admin')->user()->id;
        if (Blog::query()->create($arr)) {
            return redirect()->route('admin.blogs.index')->with(['success' => 'Thêm mới thành công']);
        }
        return redirect()->back()->withErrors('message', 'Thêm mới thất bại');
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function edit($blogId)
    {
        $blog = Blog::query()->findOrFail($blogId);

        return view(
            'admin.blogs.edit',
            [
                'blog' => $blog,
            ]
        );
    }

    public function update(UpdateRequest $request, $blogId)
    {
        $blog = Blog::query()->findOrFail($blogId);

        $arr = $request->validated();
        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $path = Storage::disk('public')->putFile('blogs', $request->file('image'));
            $arr['image'] = $path;
        }
        $blog->fill($arr);
        if ($blog->save()) {
            return redirect()->route('admin.blogs.index')->with(['success' => 'Cập nhật thành công']);
        }
        return redirect()->back()->withErrors('message', 'Cập nhật thất bại');
    }

    public function destroy($blogId)
    {
        Blog::destroy($blogId);

        return response()->json([
            'success' => 'Xóa thành công',
        ]);
    }
}
