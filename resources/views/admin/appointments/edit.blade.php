@php use App\Enums\VoucherTypeEnum; @endphp
@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('flatpicker/flatpickr.min.css') }}">
@endpush
@section('content')
    <div class="col-12">
        <form method="post" action="{{ route('admin.appointments.update', $appointment) }}" class="needs-validation"
              id="form-edit"
              novalidate>
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Khách hàng</label>
                        <input type="text" class="form-control" name="name_booker" readonly
                               value="{{ $appointment->name_booker }}">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" class="form-control" name="phone_booker" readonly
                               value="{{ $appointment->phone_booker }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" readonly
                               value="{{ $appointment->email_booker }}">
                    </div>
                    <div class="form-group">
                        <label>Số người</label>
                        <input type="text" class="form-control" name="number_people" readonly
                               value="{{ $appointment->number_people }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Ngày đặt</label>
                        <input class="form-control" name="date" required id="date">
                    </div>
                    <div class="form-group">
                        <label>Khung giờ</label>
                        <select class="form-control" name="time_id">
                            @foreach($times as $time)
                                <option value="{{ $time->id }}"
                                        @if($appointment->time_id === $time->id)
                                            selected
                                    @endif
                                >
                                    {{ $time->time_display }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <select class="form-control" name="status">
                            @foreach($arrAppointmentStatus as $option => $value)
                                <option value="{{ $value }}"
                                        @if($appointment->status === $value)
                                            selected
                                    @endif
                                >
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nhân viên phục vụ</label>
                        <select class="form-control" name="admin_id">
                            <option value="{{ null }}">-- Chọn nhân viên --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                        @if($appointment->admin_id === $employee->id)
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
                        <caption style="caption-side:top" class="fs-4">Dịch vụ</caption>
                        <thead>
                        <tr>
                            <th>Danh mục</th>
                            <th>Dịch vụ</th>
                            <th>Thời gian(phút)</th>
                            <th>Giá(VNĐ)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $appointment->service->category->name }}</td>
                            <td>{{ $appointment->service->name }}</td>
                            <td>{{ $appointment->duration }}</td>
                            <td class="price">{{ $appointment->price_display }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group mb-3 col-12">
                    <label>Ghi chú</label>
                    <textarea class="form-control" name="note" rows="5" readonly>{{ $appointment->note }}</textarea>
                </div>
            </div>

            <div class="col-4 form-horizontal float-end">
                <div class="form-group row">
                    <label class="col-3 col-form-label">Voucher</label>
                    <select class="col-9 form-control" name="voucher_id" id="voucher">
                        @if($appointment->customer)
                            <option value={{ null }}>-- Chọn voucher --</option>
                            @foreach($vouchers as $voucher)
                                <option value="{{ $voucher->id }}"
                                        data-value="{{ $voucher->value }}"
                                        data-type="{{ $voucher->type }}"
                                        data-min-spend="{{ $voucher->min_spend }}"
                                        data-max-spend="{{ $voucher->max_spend }}"
                                        @if($appointment->voucher_id === $voucher->id)
                                            selected
                                    @endif
                                >
                                    {{ $voucher->name }}
                                </option>
                            @endforeach
                        @else
                            <option value="-1">-- Khách hàng chưa đăng ký tài khoản --</option>
                        @endif
                    </select>
                </div>
                <div class="voucher-error text-danger"></div>

                <div class="form-group row">
                    <span class="col-3 col-form-label">Tiền giảm voucher</span>
                    <span class="col-9 d-flex align-items-center fs-4" id="discount_price">{{ $appointment->price - $appointment->total_price }}VND</span>
                </div>
                <div class="form-group row">
                    <span class="col-3 col-form-label">Tổng tiền</span>
                    <span class="col-9 d-flex align-items-center fs-4" id="total_price">{{ $appointment->total_price }}VND</span>
                </div>
                <button class="btn btn-primary mb-3" id="btn-submit" type="submit">Cập nhật</button>
            </div>
        </form>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('flatpicker/flatpickr.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#date").flatpickr({
                dateFormat: "d-m-Y",
                defaultDate: "{{ $appointment->date_display }}"
            });
            let voucher_element = $('#voucher');
            let total_price_element = $('#total_price');
            let price_element = $('.price');
            let voucher_error = $('.voucher-error');
            let discount_price_element = $('#discount_price');

            function format_price(price) {
                return parseFloat(price.replace(/,/g, ''));
            }

            function format_price_value(price) {
                let price_value = format_price(price)
                return price_value.toLocaleString('vi-VN', {style: 'currency', currency: 'VND'});
            }

            voucher_element.on('change', function () {
                let price = price_element.text();
                console.log('price', price);
                let price_value = format_price(price);
                let price_format = format_price_value(price);

                if ($(this).val() === '') {
                    total_price_element.text(price_format);
                    discount_price_element.text('0VND');
                    return;
                }
                let voucher_type = $(this).children("option:selected").data('type');
                let voucher_value = $(this).children("option:selected").data('value');
                let min_spend = $(this).children("option:selected").data('min-spend');
                let max_spend = $(this).children("option:selected").data('max-spend');
                let max_spend_format = format_price_value(max_spend);
                let min_spend_format = format_price_value(min_spend);
                console.log('min_spend', min_spend);
                console.log('price_value', price_value);
                if (min_spend > price_value) {
                    total_price_element.text(price_format);
                    voucher_error.text('Voucher này chỉ áp dụng cho đơn hàng từ ' + min_spend_format + ' trở lên');
                    voucher_element.val('');
                    return;
                } else {
                    voucher_error.text('');
                }

                let total_price_after_discount = 0;
                let discount = 0;
                if (voucher_type === {{ VoucherTypeEnum::PHAN_TRAM }}) {
                    console.log('voucher_value', voucher_value);
                    console.log('total_price_value', price_value);

                    discount = price_value * voucher_value / 100;
                    if (discount > max_spend) {
                        discount = max_spend;
                        $('#max_discount').text('Tối đa ' + max_spend_format);
                    }

                    if (discount > price_value) {
                        discount = price_value;
                    }

                    total_price_after_discount = price_value - discount;
                    discount_price_element.text(discount.toLocaleString('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }));
                } else {
                    if (voucher_value > price_value) {
                        voucher_value = price_value;
                    }

                    total_price_after_discount = price_value - voucher_value;
                    discount_price_element.text(voucher_value.toLocaleString('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }));
                }
                let total_price_after_discount_format = total_price_after_discount.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
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


