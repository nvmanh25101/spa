@php use App\Enums\VoucherTypeEnum; @endphp
@extends('customer.layouts.master')
@push('css')
    {{--    <link rel="stylesheet" href="{{ asset('css/customer/cart.css') }}">--}}
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/08/slide-1.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Đặt hàng
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
                        <a href="#billing-information" data-toggle="tab" aria-expanded="false"
                           class="nav-link rounded-0 active">
                            <i class="mdi mdi-account-circle font-18"></i>
                            <span class="d-none d-lg-block">Thông tin đơn hàng</span>
                        </a>
                    </li>
                </ul>

                <!-- Steps Information -->
                <div class="tab-content">
                    <!-- Billing Content-->
                    <form action="{{ route('orders.store') }}" method="post" class="needs-validation" novalidate>
                        @csrf
                        <div class="tab-pane show active" id="billing-information">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h4 class="mt-2">Thông tin đơn hàng</h4>

                                    <p class="text-muted mb-4">Điền vào mẫu dưới đây.</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="billing-first-name">Tên người nhận<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input class="form-control" type="text" name="name_receiver" required
                                                       value="{{ auth()->user()->name }}"
                                                       placeholder="Nhập tên người nhận" id="billing-first-name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="billing-phone">Số điện thoại người nhận<span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" name="phone_receiver" type="text" required
                                                       value="{{ auth()->user()->phone }}"
                                                       placeholder="(xx) xxx xxxx xxx"
                                                       id="billing-phone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="billing-address">Địa chỉ<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input class="form-control" type="text" name="address" required
                                                       placeholder="Nhập địa chỉ cụ thể"
                                                       value="{{ auth()->user()->address }}"
                                                       id="billing-address">
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="billing-town-city">Quận / Huyện<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input class="form-control" type="text" name="district" required
                                                       value="{{ auth()->user()->district }}"
                                                       placeholder="Nhập quận / huyện" id="billing-town-city">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="billing-state">Tỉnh / Thành Phố<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input class="form-control" type="text" name="city" required
                                                       value="{{ auth()->user()->city }}"
                                                       placeholder="Nhập tỉnh / thành phố"
                                                       id="billing-state">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <a href="{{ route('cart.index') }}"
                                               class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
                                                <i class="mdi mdi-arrow-left"></i> Quay trở lại giỏ hàng </a>
                                        </div> <!-- end col -->
                                        <div class="col-sm-6">
                                            <div class="text-sm-right">
                                                <button class="btn btn-danger" type="submit">
                                                    <i class="mdi mdi-truck-fast mr-1"></i> Đặt hàng
                                                </button>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>
                                <div class="col-lg-4">
                                    <div class="border p-3 mt-4 mt-lg-0 rounded">
                                        <h4 class="header-title mb-3">Tóm tắt đơn hàng</h4>

                                        <div class="table-responsive">
                                            <table class="table table-centered mb-0">
                                                <tbody>
                                                @foreach($cart->products as $item)
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
                                                        0
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
                                                        <span id="discount_price"></span>
                                                    </td>
                                                </tr>
                                                <tr class="text-right">
                                                    <td>
                                                        <h5 class="m-0">Tổng tiền:</h5>
                                                    </td>
                                                    <td class="text-right font-weight-semibold">
                                                        <span id="totalPrice"></span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <span id="max_discount"></span>

                                    <div class="alert alert-warning mt-3" role="alert">
                                        Sử dụng voucher để giảm giá !
                                    </div>

                                    <div class="input-group mt-3">
                                        <select class="form-control validate-control" id="voucher"
                                                name="voucher_id">
                                            <option value="{{ null }}">- Chọn voucher -</option>
                                            @if($vouchers)
                                                @foreach($vouchers as $item)
                                                    <option value="{{ $item->id }}"
                                                            data-value="{{ $item->value }}"
                                                            data-type="{{ $item->type }}"
                                                            data-min-spend="{{ $item->min_spend }}"
                                                            data-max-spend="{{ $item->max_spend }}"
                                                    >{{ $item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row-->
                        </div>
                    </form>
                    <!-- End Billing Information Content-->

                </div> <!-- end tab content-->

            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
@endsection
@push('js')
    <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>\
    <script>
        $(document).ready(function () {
            let voucher_element = $('#voucher');
            let discount_price_element = $('#discount_price');
            let alert_element = $('.alert-warning');
            let sub_price_element = $('#subPrice');
            let total_price_element = $('#totalPrice');

            updateTotalPrice();

            function updateTotalPrice() {
                let total = 0;

                $(".product").each(function () {
                    let price = parseFloat($(this).find(".price").text().replace(/[^\d]/g, ''));
                    total += price;
                });
                let formattedPrice = total.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'});
                sub_price_element.text(formattedPrice);
                total_price_element.text(formattedPrice);
            }

            voucher_element.on('change', function () {
                let price = sub_price_element.text();
                let price_value = parseFloat(price.replace(/[^\d]/g, ''));
                let price_format = price_value.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'});
                if ($(this).val() === '') {
                    total_price_element.text(price_format);
                    discount_price_element.text('');
                    return;
                }
                let voucher_type = $(this).children("option:selected").data('type');
                let voucher_value = $(this).children("option:selected").data('value');
                let min_spend = $(this).children("option:selected").data('min-spend');
                let max_spend = $(this).children("option:selected").data('max-spend');
                let min_spend_value = parseFloat(min_spend.replace(/[^\d.-]/g, ''));
                let min_spend_format = min_spend_value.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'})

                if (min_spend > price_value) {
                    total_price_element.text(price_format);
                    alert_element.text('Voucher này chỉ áp dụng cho đơn hàng từ ' + min_spend_format + ' trở lên');
                    return;
                } else {
                    alert_element.text('Sử dụng voucher để giảm giá !');
                }

                let total_price = total_price_element.text();
                let total_price_value = parseFloat(total_price.replace(/[^\d]/g, ''));
                isNaN(total_price_value) ? total_price_value = 0 : total_price_value;
                let total_price_after_discount = 0;
                let discount = 0;
                if (voucher_type === {{ VoucherTypeEnum::PHAN_TRAM }}) {
                    discount = total_price_value * voucher_value / 100;
                    if (discount > max_spend) {
                        discount = max_spend;
                        $('#max_discount').text('Tối đa: ' + max_spend + ' đ');

                    }
                    total_price_after_discount = total_price_value - discount;
                    discount_price_element.text(discount + ' đ');
                } else {
                    total_price_after_discount = total_price_value - voucher_value;
                    discount_price_element.text(voucher_value + ' đ');
                }
                let total_price_after_discount_format = total_price_after_discount.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                })
                total_price_element.text(total_price_after_discount_format);
            });
        });
    </script>
@endpush

