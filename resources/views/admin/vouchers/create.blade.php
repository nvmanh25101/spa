@php use App\Enums\VoucherApplyTypeEnum;use App\Enums\VoucherTypeEnum; @endphp
@extends('admin.layouts.master')
@push('css')
    <link href="{{ asset('css/base.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <form method="post" action="{{ route('admin.vouchers.store') }}" class="needs-validation" novalidate>
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label>Mã*</label>
                    <input type="text" class="form-control" name="code" placeholder="Mã"
                           value="{{ old('code') }}" required>
                </div>
                <div class="form-group">
                    <label>Tên*</label>
                    <input type="text" class="form-control" name="name" placeholder="Tên"
                           value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Tổng số lượng*</label>
                    <input type="number" min="1" class="form-control" name="uses_per_voucher" placeholder="Số lượng"
                           value="{{ old('uses_per_voucher') }}" required>
                </div>
                <div class="form-group">
                    <label>Số lượng cho mỗi khách hàng*</label>
                    <input type="number" class="form-control" min="1" name="uses_per_customer" placeholder="Số lượng"
                           value="{{ old('uses_per_customer') }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label>Loại áp dụng*</label>
                    <select class="form-control" name="applicable_type">
                        <option value="-1">Chọn</option>
                        @foreach($arrVoucherApplyType as $option => $value)
                            <option value="{{ $value }}"
                                    @if(old('applicable_type') === $value)
                                        selected
                                @endif
                            >
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Loại giảm*</label>
                    <select class="form-control" name="type">
                        <option value="-1">Chọn</option>
                        @foreach($arrVoucherType as $option => $value)
                            <option value="{{ $value }}"
                                    @if(old('type') === $value)
                                        selected
                                @endif
                            >
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Mức giảm*</label>
                    <input type="number" class="form-control" min="0" step="any" name="value" placeholder="Giá tiền"
                           value="{{ old('value') }}" required>
                </div>
                <div class="form-group">
                    <label>Giá trị đơn tối thiểu*</label>
                    <input type="number" class="form-control" name="min_spend" placeholder="Giá tiền" min="0" step="any"
                           value="{{ old('min_spend') }}" required>
                </div>
                <div class="form-group max_spend">
                    <label>Giá trị giảm tối đa</label>
                    <input type="number" class="form-control" name="max_spend" placeholder="Giá tiền" min="0" step="any"
                           value="{{ old('max_spend') }}">
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="form-group col-6 row mb-3">
                <label class="col-3 col-form-label">Ngày bắt đầu*</label>
                <input type="date" class="form-control col-5" name="start_date" placeholder="Ngày bắt đầu"
                       value="{{ old('start_date') }}" required>
            </div>
            <div class="form-group col-6 row mb-3">
                <label class="col-3 col-form-label">Ngày kết thúc*</label>
                <input type="date" class="form-control col-5" name="end_date" placeholder="Ngày kết thúc"
                       value="{{ old('end_date') }}" required>
            </div>
        </div>

        <button class="btn btn-primary mb-3" type="submit">Thêm</button>
    </form>
@endsection
@push('js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.max_spend').hide();
            $('select[name="type"]').change(function () {
                console.log($(this).val() === '{{ VoucherTypeEnum::PHAN_TRAM }}');
                if ($(this).val() === '{{ VoucherTypeEnum::PHAN_TRAM }}') {
                    $('.max_spend').show();
                } else {
                    $('.max_spend').hide();
                    $('input[name="max_spend"]').val('');
                }
            });
        });
    </script>
@endpush
