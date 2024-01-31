@php use App\Enums\VoucherTypeEnum; @endphp
@extends('customer.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('flatpicker/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer/booking.css') }}">
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/08/slide-1.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Đặt tour
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
                            <span class="d-none d-lg-block">Thông tin đơn đặt tour</span>
                        </a>
                    </li>
                </ul>

                <!-- Steps Information -->
                <div class="tab-content">
                    <!-- Billing Content-->
                    <form action="{{ route('reservations.store') }}" method="post" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                        <div class="tab-pane show active" id="billing-information">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h4 class="mt-2">Thông tin đơn đặt tour</h4>

                                    <p class="text-muted mb-4">Điền vào mẫu dưới đây.</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="billing-first-name">Tên người nhận<span
                                                        class="text-danger">*</span>
                                                </label>
                                                <input class="form-control" type="text" name="name_contact" required
                                                       value="{{ auth()->user()->name }}"
                                                       placeholder="Nhập tên người nhận" id="billing-first-name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="billing-phone">Số điện thoại người nhận<span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" name="phone_contact" type="text" required
                                                       value="{{ auth()->user()->phone }}"
                                                       placeholder="(xx) xxx xxxx xxx"
                                                       id="billing-phone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label>Số người</label>
                                            <select class="custom-select mb-3" name="number_people">
                                                <option value="-1" selected>Số lượng*</option>
                                                @for($i=1; $i<=10; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group col-6">
                                            <label>Email</label>
                                            <input type="email" id="bookingEmail" name="email_contact"
                                                   class="form-control validate-control" placeholder="Email*"
                                                   required
                                                   @auth
                                                       value="{{ auth()->user()->email }}"
                                                @endauth
                                            >
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label>Chọn thời gian</label>
                                        <div class="form-group col-4">
                                            <input class="form-control" id="date" name="departure_date" placeholder="Chọn ngày" required>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-sm-6">
                                            <a href="{{ route('customers.home') }}"
                                               class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
                                                <i class="mdi mdi-arrow-left"></i> Quay trở lại trang chủ </a>
                                        </div> <!-- end col -->
                                        <div class="col-sm-6">
                                            <div class="text-sm-right">
                                                <button class="btn btn-danger" type="submit">
                                                    <i class="mdi mdi-truck-fast mr-1"></i> Đặt tour
                                                </button>
                                            </div>
                                        </div> <!-- end col -->
                                    </div> <!-- end row -->
                                </div>
                                <input hidden id="tour_id" value="{{ $tour->id }}">
                                <div class="col-lg-4">
                                    <div class="border p-3 mt-4 mt-lg-0 rounded">
                                        <h4 class="header-title mb-3">Tóm tắt đơn đặt tour</h4>

                                        <div class="table-responsive">
                                            <table class="table table-centered mb-0">
                                                <tbody>
                                                    <tr class="product">
                                                        <td>
                                                            <img src="{{ asset('storage/' . $tour->image) }}"
                                                                 alt="contact-img"
                                                                 title="contact-img" class="rounded mr-2" height="48">
                                                            <p class="m-0 d-inline-block align-middle">
                                                                <a href="{{ route('customers.tour', $tour) }}"
                                                                   class="text-body font-weight-semibold">{{ $tour->name }}</a>
                                                                <br>
                                                            </p>
                                                        </td>
                                                        <td class="text-right price">
                                                            {{ $tour->prices[0]->price }} đ
                                                        </td>
                                                    </tr>

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
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('flatpicker/flatpickr.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>

    <script>
        $("#date").flatpickr({
            dateFormat: "d-m-Y",
            minDate: "today",
        });
        $(document).ready(function () {
            let voucher_element = $('#voucher');
            let discount_price_element = $('#discount_price');
            let alert_element = $('.alert-warning');
            let sub_price_element = $('#subPrice');
            let total_price_element = $('#totalPrice');
            let number_people_element = $('select[name="number_people"]');

            updateTotalPrice();

            number_people_element.on('change', function () {
                let number_people = $(this).val();
                let price = sub_price_element.text();
                let price_value = parseInt(price.replace(/[^\d]/g, ''));
                if (number_people > 0) {
                    price_value = price_value * number_people;
                }
                let discount_price = discount_price_element.text();
                let discount_price_value = parseInt(discount_price.replace(/[^\d]/g, ''));
                if (discount_price_value > 0) {
                    price_value = price_value - discount_price_value;
                }
                let price_format = price_value.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'});
                sub_price_element.text(price_format);
            });

            function updateTotalPrice() {
                let total = 0;
                let number_people = number_people_element.val();
                $(".product").each(function () {
                    let price = parseInt($(this).find(".price").text());
                    if (number_people > 0) {
                        price = price * number_people;
                    }
                    total += price;
                });

                let formattedPrice = total.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'});
                sub_price_element.text(formattedPrice);
                total_price_element.text(formattedPrice);
            }

            voucher_element.on('change', function () {
                let price = sub_price_element.text();
                let price_value = parseInt(price.replace(/[^\d]/g, ''));
                let price_format = price_value.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'});
                if ($(this).val() == '') {
                    total_price_element.text(price_format);
                    discount_price_element.text('');
                    return;
                }
                let voucher_type = $(this).children("option:selected").data('type');
                let voucher_value = $(this).children("option:selected").data('value');
                let min_spend = $(this).children("option:selected").data('min-spend');
                let max_spend = $(this).children("option:selected").data('max-spend');
                let min_spend_value = parseInt(min_spend.replace(/[^\d.-]/g, ''));
                let min_spend_format = min_spend_value.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'})

                if (min_spend > price_value) {
                    total_price_element.text(price_format);
                    alert_element.text('Voucher này chỉ áp dụng cho đơn hàng từ ' + min_spend_format + ' trở lên');
                    return;
                } else {
                    alert_element.text('Sử dụng voucher để giảm giá !');
                }

                let total_price = total_price_element.text();
                let total_price_value = parseInt(total_price.replace(/[^\d]/g, ''));
                isNaN(total_price_value) ? total_price_value = 0 : total_price_value;
                let total_price_after_discount = 0;
                let discount = 0;
                if (voucher_type === {{ VoucherTypeEnum::PHAN_TRAM }}) {
                    discount = total_price_value * voucher_value / 100;
                    if (discount > max_spend) {
                        discount = max_spend;
                        $('#max_discount').text('Tối đa: ' + max_spend + ' đ');
                    }
                    if (discount > total_price_value) {
                        discount = total_price_value;
                    }
                    total_price_after_discount = total_price_value - discount;
                    discount_price_element.text(discount + ' đ');
                } else {
                    if (voucher_value > total_price_value) {
                        voucher_value = total_price_value;
                    }
                    total_price_after_discount = total_price_value - voucher_value;
                    discount_price_element.text(voucher_value + ' đ');
                }
                let total_price_after_discount_format = total_price_after_discount.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                })
                total_price_element.text(total_price_after_discount_format);
            });

            @if(session('success'))
            $.notify('{{ session('success') }}', "success");
            @endif
            @if(session('error'))
            $.notify('{{ session('error') }}', "error");
            @endif
        });

    </script>
@endpush
