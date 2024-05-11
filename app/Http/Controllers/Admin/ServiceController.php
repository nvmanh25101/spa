<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Category\StatusEnum;
use App\Enums\Category\TypeEnum;
use App\Enums\ServiceStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Service\StoreRequest;
use App\Http\Requests\Admin\Service\UpdateRequest;
use App\Models\Category;
use App\Models\Notification;
use App\Models\PriceService;
use App\Models\Service;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    public string $ControllerName = 'Dịch vụ';

    public function __construct()
    {
        $pageTitle = Route::currentRouteAction();
        $pageTitle = explode('@', $pageTitle)[1];
        view()->share('ControllerName', $this->ControllerName);
        view()->share('pageTitle', $pageTitle);

        $arrServiceStatus = ServiceStatusEnum::getArrayView();
        view()->share('arrServiceStatus', $arrServiceStatus);

        $arrNoti = Notification::query()->get();
        view()->share('arrNoti', $arrNoti);
    }

    public function index()
    {
        return view('admin.services.index');
    }

    public function api()
    {
        return DataTables::of(Service::query())
            ->addColumn('category_name', function ($object) {
                return $object->category->name;
            })
            ->editColumn('status', function ($object) {
                return ServiceStatusEnum::getKeyByValue($object->status);
            })
            ->addColumn('edit', function ($object) {
                return route('admin.services.edit', $object);
            })
            ->addColumn('destroy', function ($object) {
                return route('admin.services.destroy', $object);
            })
            ->filterColumn('status', function ($query, $keyword) {
                if ($keyword !== '-1') {
                    $query->where('status', $keyword);
                }
            })
            ->filterColumn('category_name', function ($query, $keyword) {
                $query->whereHas('category', function ($query) use ($keyword) {
                    $query->where('id', $keyword);
                });
            })
            ->make(true);
    }

    public function store(StoreRequest $request)
    {
        $duration_price = getDurationPrice($request);

        $service = Service::query()->create($request->validated());
        if ($service) {
            $service->priceServices()->createMany($duration_price);
            return redirect()->route('admin.services.index')->with(['success' => 'Thêm mới thành công']);
        }
        return redirect()->back()->withErrors('message', 'Thêm mới thất bại');
    }

    public function create()
    {
        $categories = Category::query()->where('status', '=', StatusEnum::HOAT_DONG)
            ->where('type', '=', TypeEnum::DICH_VU)
            ->get(['id', 'name']);
        return view(
            'admin.services.create',
            [
                'categories' => $categories,
            ]
        );
    }

    public function edit($serviceId)
    {
        $categories = Category::query()->where('status', '=', StatusEnum::HOAT_DONG)
            ->where('type', '=', TypeEnum::DICH_VU)
            ->get(['id', 'name']);
        $service = Service::query()->findOrFail($serviceId);
        $service->load('priceServices');

        return view(
            'admin.services.edit',
            [
                'service' => $service,
                'categories' => $categories,
            ]
        );
    }

    public function update(UpdateRequest $request, $serviceId)
    {
        $duration_price = getDurationPrice($request);
        $price_id = $request->validated()['price_id'];
        unset($request->validated()['price_id']);
        $price_data = array_map(function ($id, $duration_price) {
            return [
                "id" => $id,
                "duration_price" => $duration_price,
            ];
        }, $price_id, $duration_price);


        $service = Service::query()->findOrFail($serviceId);
        $service->fill($request->validated());

        if ($service->save()) {
            foreach ($price_data as $item) {
                if ($item['id'] === '-1') {
                    $service->priceServices()->create($item['duration_price']);
                    continue;
                }
                $price = $service->priceServices()->whereId($item['id'])->first();
                $price->duration = $item['duration_price']['duration'];
                $price->price = $item['duration_price']['price'];
                $price->push();
            }
            return redirect()->route('admin.services.index')->with(['success' => 'Cập nhật thành công']);
        }
        return redirect()->back()->withErrors('message', 'Cập nhật thất bại');
    }

    public function destroyPrice($serviceId, $priceId)
    {
        if (PriceService::destroy($priceId)) {
            return response()->json([
                'success' => 'Xóa thành công',
            ]);
        }

        return response()->json([
            'error' => 'Xóa thất bại',
        ]);
    }

    public function destroy($serviceId)
    {
        if (Service::destroy($serviceId)) {
            return response()->json([
                'success' => 'Xóa thành công',
            ]);
        }

        return response()->json([
            'error' => 'Xóa thất bại',
        ]);
    }
}
