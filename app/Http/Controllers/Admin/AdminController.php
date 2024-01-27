<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdminStatusEnum;
use App\Enums\AdminType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\StoreRequest;
use App\Http\Requests\Admin\Admin\UpdateRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    public string $ControllerName = 'Nhân viên';

    public function __construct()
    {
        $pageTitle = Route::currentRouteAction();
        $pageTitle = explode('@', $pageTitle)[1];
        view()->share('ControllerName', $this->ControllerName);
        view()->share('pageTitle', $pageTitle);

        $arrAdminLevel = AdminType::getArrayView();
        view()->share('arrAdminLevel', $arrAdminLevel);

        $arrAdminStatus = AdminStatusEnum::getArrayView();
        view()->share('arrAdminStatus', $arrAdminStatus);
    }

    public function index()
    {
        return view('admin.employees.index');
    }

    public function api()
    {
        return DataTables::of(Admin::query())
            ->editColumn('role', function ($object) {
                return AdminType::getKeyByValue($object->role);
            })
            ->editColumn('status', function ($object) {
                return adminStatusEnum::getKeyByValue($object->status);
            })
            ->addColumn('edit', function ($object) {
                return route('admin.employees.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('admin.employees.destroy', $object);
            })
            ->filterColumn('role', function ($query, $keyword) {
                if ($keyword !== '-1') {
                    $query->where('role', $keyword);
                }
            })
            ->make(true);
    }

    public function resign()
    {
        return view('admin.employees.resign');
    }

    public function resignList()
    {
        return DataTables::of(Admin::query()->onlyTrashed())
            ->editColumn('role', function ($object) {
                return AdminType::getKeyByValue($object->role);
            })
            ->filterColumn('role', function ($query, $keyword) {
                if ($keyword !== '-1') {
                    $query->where('role', $keyword);
                }
            })
            ->make(true);
    }

    public function store(StoreRequest $request)
    {
        $password = Hash::make($request->password);
        $arr = $request->validated();
        $arr['password'] = $password;
        if (Admin::query()->create($arr)) {
            return redirect()->route('admin.employees.index')->with(['success' => 'Thêm mới thành công']);
        }
        return redirect()->back()->withErrors('message', 'Thêm mới thất bại');
    }

    public function create()
    {
        return view('admin.employees.create');
    }


    public function edit($adminId)
    {
        $admin = Admin::query()->findOrFail($adminId);

        return view(
            'admin.employees.edit',
            [
                'admin' => $admin,
            ]
        );
    }

    public function update(UpdateRequest $request, $adminId)
    {
        $admin = Admin::query()->findOrFail($adminId);
        $admin->fill($request->validated());
        if ($admin->save()) {
            return redirect()->route('admin.employees.index')->with(['success' => 'Cập nhật thành công']);
        }
        return redirect()->back()->withErrors('message', 'Cập nhật thất bại');
    }

    public function destroy($adminId)
    {
        if (auth()->user()->id == $adminId) {
            return response()->json([
                'error' => 'Bạn không thể xóa chính mình',
            ]);
        }
        $admin = Admin::query()->findOrFail($adminId);
        if ($admin->role == AdminType::QUAN_LY) {
            return response()->json([
                'error' => 'Bạn không thể xóa quản lý',
            ]);
        }

        Admin::destroy($adminId);

        return response()->json([
            'success' => 'Xóa thành công',
        ]);
    }
}
