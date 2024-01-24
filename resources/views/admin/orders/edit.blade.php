@php use App\Enums\AdminRoleEnum;use App\Enums\AdminType;use App\Enums\OrderPaymentEnum;use Illuminate\Support\Facades\Auth; @endphp
@extends('admin.layouts.master')
@section('content')
    <div class="col-12">
        <form method="post" action="{{ route('admin.orders.update', $order) }}" class="needs-validation"
              id="form-edit"
              novalidate>
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Khách hàng</label>
                        <input type="text" class="form-control" name="name_receiver" readonly
                               value="{{ $order->name_receiver }}">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" class="form-control" name="phone_receiver" readonly
                               value="{{ $order->phone_receiver }}">
                    </div>
                    <div class="form-group">
                        <label>Hình thức thanh toán</label>
                        <input type="text" class="form-control" name="payment_method" readonly
                               value="{{ OrderPaymentEnum::getKeyByValue($order->payment_method)  }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Trạng thái đơn hàng</label>
                        <select class="form-control" name="status">
                            @foreach($arrOrderStatus as $option => $value)
                                <option value="{{ $value }}"
                                        @if($order->status === $value)
                                            selected
                                    @endif
                                >
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái thanh toán</label>
                        <select class="form-control" name="payment_status"
                                @if(Auth::guard('admin')->user()->role === AdminType::VAN_CHUYEN)
                                    readonly
                            @endif
                        >
                            @foreach($arrOrderPaymentStatus as $option => $value)
                                <option value="{{ $value }}"
                                        @if($order->payment_status === $value)
                                            selected
                                    @endif
                                >
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nhân viên vận chuyển</label>
                        <select class="form-control" name="admin_id">
                            <option value="{{ null }}">-- Chọn nhân viên --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                        @if($order->admin_id === $employee->id)
                                            selected
                                    @endif
                                >
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group mb-3 col-12">
                    <table class="table table-hover table-centered mb-0">
                        <caption style="caption-side:top" class="fs-4">Sản phẩm</caption>
                        <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền(VNĐ)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->products as $item)
                            <tr class="product">
                                <td>
                                    <p class="m-0 d-inline-block align-middle">
                                        <a href="{{ route('customers.product', $item) }}"
                                           class="text-body font-weight-semibold">{{ $item->name }}</a>
                                    </p>
                                </td>
                                <td>{{ $item->pivot->price }} đ</td>
                                <td>{{ $item->pivot->quantity }}</td>
                                <td>{{ $item->price * $item->pivot->quantity }} đ</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-4 form-horizontal float-end">
                <div class="form-group row">
                    <label class="col-3 col-form-label">Voucher</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="voucher" id="voucher" disabled
                               value="{{ $order->voucher->name }}">
                    </div>
                    <div class="voucher-error text-danger"></div>

                    <div class="form-group row">
                        <span class="col-3 col-form-label">Tiền giảm voucher</span>
                        <span class="col-9 d-flex align-items-center fs-4">{{ $order->price - $order->total }}VND</span>
                    </div>
                    <div class="form-group row">
                        <span class="col-3 col-form-label">Tổng tiền</span>
                        <span class="col-9 d-flex align-items-center fs-4">{{ $order->total }}VND</span>
                    </div>
                    <button class="btn btn-primary mb-3" id="btn-submit" type="submit">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            @if(session('success'))
            $.notify('{{ session('success') }}', "success");
            @endif
            @if(session('error'))
            $.notify('{{ session('error') }}', "error");
            @endif
        });
    </script>
@endpush


