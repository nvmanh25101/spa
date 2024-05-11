@php use Carbon\Carbon; @endphp
@extends('customer.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/customer/product_base.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/customer/products.css') }}" type="text/css">
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/08/slide-1.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Blog
            </h1>
        </div>
    </div>
@endsection
@section('content')
    @foreach($blogs as $blog)
        <div class="blog-list-item mb-3">
            <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="blog-list-info">
                    <div class="blog-tag">
                        <div class="blog-date text-uppercase">
                            {{ $blog->created_date }}
                        </div>
                    </div>
                    <h3 class="blog-title">
                        <a href="">{{ $blog->title }}</a>
                    </h3>
                    <a href="{{ route('customers.blog', $blog) }}" class="read-more">Đọc thêm</a>
                </div>
            </div>
            <div class="col-md-7 col-sm-6 col-xs-12">
                <div class="blog-img">
                    <a href="{{ route('customers.blog', $blog) }}" class="effect-img3 plus-zoom">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="" class="img-reponsive">
                    </a>
                </div>
            </div>
        </div>
    @endforeach
    <div class="row">
        <div class="col-md-12">
            {{ $blogs->links() }}
        </div>
    </div>

@endsection
@push('js')
    <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/main_product.js') }}"></script>
@endpush

