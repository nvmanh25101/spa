@extends('customer.layouts.master')
@push('css')
    <link href="{{ asset('css/customer/home.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="row d-flex justify-content-center mb-3">
        <div class="tab-content col-9 search">
            <div role="tabpanel" class="tab-pane active" id="form-tour">
                <div class="box-form-tour-inner page-child">
                    <h4 class="box-title ps-1 pt-1">Du lịch 5 châu, không đâu rẻ bằng</h4>
                    <div class="box-search-tour pb-1 ps-1">
                        <label for="search-tour-text">Nhập địa điểm bạn muốn đến</label>
                        <form action="{{ route('customers.home') }}">
                            <span class="twitter-typeahead" style="position: relative; display: inline-block;">
                                <input type="text" placeholder="Địa điểm du lịch" name="destination" id="search-tour-text" class="search-tour-text form-control typeahead tt-input mb-2" autocomplete="off" spellcheck="false" dir="auto">
                            </span>
                            <button class="box-button-tour btn btn-primary mb-1">Tìm kiếm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row engoc-row-equal">
        @if($tours->count() === 0)
            <div class="text-center">
                <span class="fs-2">Không có tour nào</span>
            </div>
        @endif
        @foreach($tours as $tour)
                <div class="col-md-6 col-lg-3">

                    <!-- Simple card -->
                    <div class="card d-block">
                        <img class="card-img-top" src="{{ asset('storage/' . $tour->image) }}" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title"><a href="{{ route('customers.tour', $tour) }}" class="">{{ $tour->name }}</a></h5>
                            <p class="card-text"></p>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div>
        @endforeach
    </div>
    {{ $tours->links() }}
@endsection
@push('js')
@endpush
