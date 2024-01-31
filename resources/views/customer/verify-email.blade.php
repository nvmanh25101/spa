@extends('customer.layouts.master')
@push('css')
    <link href="{{ asset('css/customer/signup.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/09/banner_demo.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Xác thực email
            </h1>
        </div>
    </div>
@endsection
@section('content')
    <div class="col-lg-5 mt-4">
        <div class="card">
            <div class="card-body p-4">
                <div class="text-center m-auto">
                    <h4 class="text-dark-50 text-center mt-4 font-weight-bold">Vui lòng kiểm tra email của bạn</h4>
                    <p class="text-muted mb-4">
                        Một email đã được gửi tới email của bạn.
                        Vui lòng kiểm tra email và nhấp vào liên kết đi kèm để xác thực tài khoản
                    </p>
                </div>
                <div class="form-group mb-0 text-center">
                    <form action="{{ route('verification.send') }}">
                        <button class="btn btn-primary">Gửi lại link xác thực</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
