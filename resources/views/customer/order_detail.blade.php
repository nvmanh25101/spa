@php use App\Enums\OrderPaymentEnum;use App\Enums\OrderPaymentStatusEnum;use App\Enums\OrderStatusEnum; @endphp
@extends('customer.layouts.master')
@push('css')
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/08/slide-1.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Đơn hàng của bạn
            </h1>
        </div>
    </div>
@endsection
@section('content')
    <div class="col-12">
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
                           value="@if ($order->payment_method) {{ OrderPaymentEnum::getKeyByValue($order->payment_method) }} @endif">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Trạng thái đơn hàng</label>
                    <input type="text" class="form-control" name="payment_method" readonly
                           value="{{ OrderStatusEnum::getKeyByValue($order->status)  }}">
                </div>
                <div class="form-group">
                    <label>Trạng thái thanh toán</label>
                    <input type="text" class="form-control" name="payment_method" readonly
                           value="{{ OrderPaymentStatusEnum::getKeyByValue($order->payment_status)  }}">
                </div>
                <div class="form-group">
                    <label>Nhân viên vận chuyển</label>
                    <input type="text" class="form-control" name="payment_method" readonly
                           value="{{ $order->admin->name ?? ''  }}">
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
                           value="{{ $order->voucher->name ?? '' }}">
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
            </div>
        </div>
    </div>
@endsection

