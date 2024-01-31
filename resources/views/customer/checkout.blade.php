@php use App\Enums\PaymentEnum;use App\Enums\VoucherTypeEnum; @endphp
@extends('customer.layouts.master')
@push('css')
    {{--    <link rel="stylesheet" href="{{ asset('css/customer/favorite.css') }}">--}}
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/08/slide-1.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Thanh toán đơn đặt tour
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
                            <form action="{{ route('reservations.update', $reservation) }}" method="post">
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
                                                           value="{{ PaymentEnum::CHUYEN_KHOAN }}"
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
                                                           value="{{ PaymentEnum::TAI_CUA_HANG }}"
                                                           class="custom-control-input">
                                                    <label class="custom-control-label font-16 font-weight-bold"
                                                           for="BillingOptRadio4">Thanh toán tại cửa hàng</label>
                                                </div>
                                                <p class="mb-0 pl-3 pt-1">Thanh toán tại cửa hàng.</p>
                                            </div>
                                            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="BillingOptRadio5" name="payment_method"
                                                           value="{{ PaymentEnum::TAI_VAN_PHONG }}"
                                                           class="custom-control-input">
                                                    <label class="custom-control-label font-16 font-weight-bold"
                                                           for="BillingOptRadio5">Thanh toán tại văn phòng</label>
                                                </div>
                                                <p class="mb-0 pl-3 pt-1">Thanh toán tại văn phòng.</p>
                                            </div>
                                            <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end Cash on Delivery box-->

                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <a href="{{ route('favorite.index') }}"
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
                        </div> <!-- end row-->
                    </div>
                    <!-- End Payment Information Content-->

                </div> <!-- end tab content-->

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
@endsection
