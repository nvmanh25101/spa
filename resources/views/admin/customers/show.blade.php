@extends('admin.layouts.master')
@push('css')
@endpush
@section('content')
    <div class="col-12">
        <div class="row">
            <div class="form-group col-6">
                <label>Họ tên</label>
                <input type="text" class="form-control" name="name" value="{{ $customer->name }}" required>
            </div>
            <div class="form-group col-6">
                <label>Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ $customer->email }}"
                       readonly="">
            </div>
        </div>
        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" class="form-control" name="phone" value="{{ $customer->phone }}" required>
        </div>
        <div class="row">
            <div class="form-group col-4">
                <label>Địa chỉ</label>
                <input type="text" class="form-control" name="address" value="{{ $customer->address }}" required>
            </div>
            <div class="form-group col-4">
                <label>Quận/Huyện</label>
                <input type="text" class="form-control" name="address" value="{{ $customer->district }}" required>
            </div>
            <div class="form-group col-4">
                <label>Thành phố</label>
                <input type="text" class="form-control" name="address" value="{{ $customer->city }}" required>
            </div>
        </div>
    </div>
@endsection
