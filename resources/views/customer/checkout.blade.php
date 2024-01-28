@php use App\Enums\OrderPaymentEnum;use App\Enums\VoucherTypeEnum; @endphp
@extends('customer.layouts.master')
@push('css')
    {{--    <link rel="stylesheet" href="{{ asset('css/customer/cart.css') }}">--}}
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/08/slide-1.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Thanh toán đơn hàng
            </h1>
        </div>
    </div>
@endsection
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Checkout Steps -->
                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#payment-information" data-toggle="tab" aria-expanded="false"
                           class="nav-link rounded-0 active">
                            <i class="mdi mdi-cash-multiple font-18"></i>
                            <span class="d-none d-lg-block">Thông tin thanh toán</span>
                        </a>
                    </li>
                </ul>

                <!-- Steps Information -->
                <div class="tab-content">

                    <!-- Payment Content-->
                    <div class="tab-pane active" id="payment-information">
                        <div class="row">
                            <form action="{{ route('orders.update', $order) }}" method="post" class="col-lg-8">
                                @csrf
                                @method('PATCH')
                                <div class="col-lg-12">
                                    <h4 class="mt-2">Thanh toán</h4>

                                    <p class="text-muted mb-4">Chọn phương thức thanh toán.</p>

                                    <!-- Pay with Paypal box-->
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="BillingOptRadio2" name="payment_method"
                                                           value="{{ OrderPaymentEnum::CHUYEN_KHOAN }}"
                                                           class="custom-control-input">
                                                    <label class="custom-control-label font-16 font-weight-bold"
                                                           for="BillingOptRadio2">Vnpay</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                <img src="https://vnpay.vn/assets/images/logo-icon/logo-primary.svg"
                                                     height="25"
                                                     alt="paypal-img">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end Pay with Paypal box-->

                                    <!-- Cash on Delivery box-->
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="BillingOptRadio4" name="payment_method"
                                                           value="{{ OrderPaymentEnum::TIEN_MAT }}"
                                                           class="custom-control-input">
                                                    <label class="custom-control-label font-16 font-weight-bold"
                                                           for="BillingOptRadio4">Thanh toán khi giao hàng</label>
                                                </div>
                                                <p class="mb-0 pl-3 pt-1">Thanh toán bằng tiền mặt khi đơn hàng của bạn
                                                    được giao.</p>
                                            </div>
                                            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                <img src="{{ asset('storage/cod.png') }}" height="22" alt="paypal-img">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end Cash on Delivery box-->

                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <a href="{{ route('cart.index') }}"
                                               class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
                                                <i class="mdi mdi-arrow-left"></i> Quay trở lại giỏ hàng </a>
                                        </div> <!-- end col -->
                                        <div class="col-sm-6">
                                            <div class="text-sm-right">
                                                <button class="btn btn-danger" type="submit">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> Hoàn thành
                                                </button>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row-->

                                </div>
                            </form>
                            <!-- end col -->

                            <div class="col-lg-4">
                                <div class="border p-3 mt-4 mt-lg-0 rounded">
                                    <h4 class="header-title mb-3">Tóm tắt đơn hàng</h4>

                                    <div class="table-responsive">
                                        <table class="table table-centered mb-0">
                                            <tbody>
                                            @foreach($order->products as $item)
                                                <tr class="product">
                                                    <td>
                                                        <img src="{{ asset('storage/' . $item->image) }}"
                                                             alt="contact-img"
                                                             title="contact-img" class="rounded mr-2" height="48">
                                                        <p class="m-0 d-inline-block align-middle">
                                                            <a href="{{ route('customers.product', $item) }}"
                                                               class="text-body font-weight-semibold">{{ $item->name }}</a>
                                                            <br>
                                                            <small>{{ $item->pivot->quantity }} x {{ $item->price }}
                                                                đ</small>
                                                        </p>
                                                    </td>
                                                    <td class="text-right price">{{ $item->price * $item->pivot->quantity }}
                                                        đ
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr class="text-right">
                                                <td>
                                                    <h6 class="m-0">Tổng phụ:</h6>
                                                </td>
                                                <td class="text-right" id="subPrice">
                                                    {{ $order->price }}
                                                </td>
                                            </tr>
                                            <tr class="text-right">
                                                <td>
                                                    <h6 class="m-0">Phí vận chuyển:</h6>
                                                </td>
                                                <td class="text-right">
                                                    Miễn phí
                                                </td>
                                            </tr>
                                            <tr class="text-right">
                                                <td>
                                                    <h6 class="m-0">Giảm giá:</h6>
                                                </td>
                                                <td class="text-right discount_price">
                                                    <span id="discount_price">{{ $order->price - $order->total }}</span>
                                                </td>
                                            </tr>
                                            <tr class="text-right">
                                                <td>
                                                    <h5 class="m-0">Tổng tiền:</h5>
                                                </td>
                                                <td class="text-right font-weight-semibold">
                                                    <span id="totalPrice">{{ $order->total }}</span>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- end col -->
                        </div> <!-- end row-->
                    </div>
                    <!-- End Payment Information Content-->

                </div> <!-- end tab content-->

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
@endsection
@push('js')
    <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            let voucher_element = $('#voucher');
            let discount_price_element = $('#discount_price');
            let alert_element = $('.alert-warning');
            let sub_price_element = $('#subPrice');
            let total_price_element = $('#totalPrice');

            updateTotalPrice();

            function updateTotalPrice() {
                let total = total_price_element.text();


                let formattedPrice = total.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'});
                total_price_element.text(formattedPrice);
            }


        @if(session('success'))
        $.notify('{{ session('success') }}', "success");
        @endif
        @if(session('error'))
        $.notify('{{ session('error') }}', "error");
        @endif
    </script>
@endpush

