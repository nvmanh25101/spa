@php use Carbon\Carbon; @endphp
@extends('customer.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/customer/tours.css') }}" type="text/css">
@endpush
@section('content')
    <div class="single-product-detail col-10">
        <div class="row">
            <div class="row">
                <div class="col-8">
                    <div class="product-img-slide">
                        <div class="product-images">
                            <img
                                    src="{{ asset('storage/' . $tour->image) }}" alt="photo"
                                    class="img-responsive">
                        </div>
                    </div>
                </div>
                <div class="col-4 price">
                    <p>
                        @foreach($tour->prices as $item)
                            @php
                                $stringToCheck = $item->age_group;

                                $pattern = "NGƯỜI LỚN";

                                $startsWithPattern = str_starts_with($stringToCheck, $pattern);
                            @endphp
                            @if($startsWithPattern)
                                <span class="price-tour fs-4">{{ number_format($item->price) }} VND/người</span>
                            @endif
                        @endforeach

                    </p>
                    <div class="flex product-rating">
                        <div class="number-rating fs-6 mb-4">( {{ $reviews->count() }} đánh giá )</div>
                    </div>
                    <a href="{{ route('reservations.booking', $tour) }}" class="btn btn-primary fs-5 btn-booking">Đặt tour</a>
                    <a href="{{ route('favorite.store', $tour) }}" class="btn btn-primary mt-3 btn-booking">Thêm vào danh sách yêu thích</a>
                </div>
            </div>

            <div class="col-12 mt-3">
                @include('customer.layouts.errors')
                <div class="box-tlb-tour mb-3">
                    <table class="table table-hover table-centered mb-0">
                        <tbody>
                        <tr>
                            <td><i class="fa fa-map-marker-alt" aria-hidden="true"></i>
                                @foreach($tour->destinations as $item)
                                    {{ $item->name }},
                                @endforeach
                            </td>
                            <td><i class="fa fa-clock-o" aria-hidden="true"></i> <span>{{ $tour->duration }}</span></td>
                            <td><span>Phương tiện: </span>
                                @if($tour->vehicle == \App\Enums\TourVehicleEnum::O_TO)
                                    <img class="img-traffic" title="Xe"
                                         src="https://www.vietnambooking.com/wp-content/themes/vietnambooking_master/images/index/tour/icon_traffic/o_to.png"
                                         alt="o_to">
                                @elseif($tour->vehicle == \App\Enums\TourVehicleEnum::MAY_BAY)
                                    <img class="img-traffic" title="Máy bay"
                                         src="https://www.vietnambooking.com/wp-content/themes/vietnambooking_master/images/index/tour/icon_traffic/may_bay.png"
                                         alt="may_bay">
                                @elseif($tour->vehicle == \App\Enums\TourVehicleEnum::TAU_HOA)
                                    <img class="img-traffic" title="Tàu thủy"
                                         src="https://www.vietnambooking.com/wp-content/themes/vietnambooking_master/images/index/tour/icon_traffic/tau_thuy.png"
                                         alt="tau_thuy">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span class="title-tour">Mã tour: </span>
                                <span class="id-tour">{{ $tour->code }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Khởi hành:
                                <span>{{ $tour->departure_time }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-service-tour mb-3">
                    <h4 class="title-service">Dịch vụ kèm theo</h4>
                    <ul class="list-extra-services list-group-horizontal list-group">
                        @foreach($tour->services as $item)
                            <li class="list-group-item">&nbsp;&nbsp;{{ $item->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <p>{!! nl2br($tour->description) !!}</p>
                <h3 class="product-title"><a href="#">{{ $tour->name }}</a></h3>
                @if(session('error'))
                    <div
                            class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                            role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Lỗi - </strong> {{ session('error') }}!
                    </div>
                @endif
                <div class="accordion custom-accordion" id="custom-accordion-one">
                    <div class="card mb-4">
                        <div class="card-header panel-heading" id="headingFour">
                            <h5 class="m-0">
                                <a class="custom-accordion-title d-block py-1"
                                   data-toggle="collapse" href="#collapseFour"
                                   aria-expanded="true" aria-controls="collapseFour">
                                    Chương trình tour
                                    <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                </a>
                            </h5>
                        </div>

                        <div id="collapseFour" class="collapse show"
                             aria-labelledby="headingFour"
                             data-parent="#custom-accordion-one">
                            <div class="card-body panel-body">
                                @foreach($tour->schedules as $item)
                                    <div class="row">
                                        <div class="col-12">
                                            <h5>Ngày: {{ $item->day }}: {{ $item->activity }}</h5>
                                            <p>{!! nl2br($item->description) !!}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header panel-heading" id="headingFive">

                            <h5 class="m-0">
                                <a class="custom-accordion-title collapsed d-block py-1"
                                   data-toggle="collapse" href="#collapseFive"
                                   aria-expanded="false" aria-controls="collapseFive">
                                    Dịch vụ bao gồm
                                    <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseFive" class="collapse"
                             aria-labelledby="headingFive"
                             data-parent="#custom-accordion-one">
                            <div class="card-body panel-body">
                                <table cellpadding="1" cellspacing="1" class="table table-hover table-centered mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">
                                            KHỞI HÀNH
                                        </th>
                                        <th colspan="3" rowspan="1" scope="col">
                                            <p>
                                                GIÁ TOUR (VND/ KHÁCH)
                                            </p>
                                        </th>
                                        <th rowspan="1" scope="col">
                                            PHỤ THU PHÒNG ĐƠN
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="1">
                                            &nbsp;
                                        </td>
                                        @foreach($tour->prices as $item)
                                            <td>
                                                <p style="text-align: center;">
                                                    {{ $item->age_group }}
                                                </p>
                                            </td>
                                        @endforeach
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="1">
                                            &nbsp;{{ $tour->departure_time }}
                                        </td>
                                        @foreach($tour->prices as $item)
                                            <td>
                                                <p style="text-align: center;">
                                                    {{ $item->price ?? 'Liên hệ' }}
                                                </p>
                                            </td>
                                        @endforeach
                                        <td colspan="1" rowspan="6">
                                            <p style="text-align: center;">
                                                <span style="color:#FF0000;"><strong>Liên hệ</strong></span>
                                            </p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="">
                                    <h5>Giá tour bao gồm</h5>
                                    <p>{!! nl2br($tour->price_include) !!}--}}</p>
                                </div>
                                <div class="">
                                    <h5>Giá tour không bao gồm</h5>
                                    <p>{!! nl2br($tour->price_exclude) !!}--}}</p>
                                </div>
                                @if($tour->price_childen)
                                    <div class="">
                                        <h5>Giá tour khi có trẻ em</h5>
                                        <p>{!! nl2br($tour->price_childen) !!}--}}</p>
                                    </div>
                                @endif
                                @if($tour->note)
                                    <div class="">
                                        Lưu ý: <br>
                                        <p>{!! nl2br($tour->note) !!}--}}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header panel-heading" id="headingSix">
                            <h5 class="m-0">
                                <a class="custom-accordion-title collapsed d-block py-1"
                                   data-toggle="collapse" href="#collapseSix"
                                   aria-expanded="false" aria-controls="collapseSix">
                                    Đánh giá @if($reviews->count() > 0)
                                        ({{ $reviews->count() }})
                                    @endif
                                    <i class="mdi mdi-chevron-down accordion-arrow"></i>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                             data-parent="#custom-accordion-one">
                            <div class="card-body  panel-body">
                                <ul class="review-content">
                                    @foreach($reviews as $review)
                                        <li class="element-review">
                                            <div class="flex align-items-center justify-content-between">
                                                <div class="review-left">
                                                    <p class="r-name">{{ $review->customer->name }}</p>
                                                    <p class="r-date">{{ Carbon::parse($review->reivew_at)->format('d/m/Y')}}</p>
                                                </div>
                                                <div class="group-star">
                                                    @for($i=1; $i <= 5; $i++)
                                                        @if($i < $review->rating)
                                                            <span class="star-full"><i
                                                                        class="mdi mdi-star"></i></span>
                                                        @else
                                                            <span class="star-out"><i
                                                                        class="mdi mdi-star-outline"></i></span>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="r-desc">
                                                {{ $review->content }}
                                            </p>
                                            @if($review->reply)
                                                <hr>
                                                <p class="r-desc">
                                                    Phản hồi: {{ $review->reply }}
                                                </p>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                @auth
                                    @if($reviews->where('customer_id', auth()->user()->id)->count() > 0)
                                        <div
                                                class="alert alert-info alert-dismissible bg-danger text-white border-0 fade show"
                                                role="alert">
                                            <strong>Thông báo - </strong> Bạn đã đánh giá tour này!
                                        </div>
                                    @elseif($order_count > 0)
                                        <form class="review-form" action="{{ route('tours.review', $tour) }}"
                                              method="post">
                                            @csrf
                                            <h3 class="review-heading">Đánh giá của bạn</h3>
                                            <div class="rate">
                                                <input type="radio" id="star5" name="rating" value="5"/>
                                                <label for="star5" title="text">5 stars</label>
                                                <input type="radio" id="star4" name="rating" value="4"/>
                                                <label for="star4" title="text">4 stars</label>
                                                <input type="radio" id="star3" name="rating" value="3"/>
                                                <label for="star3" title="text">3 stars</label>
                                                <input type="radio" id="star2" name="rating" value="2"/>
                                                <label for="star2" title="text">2 stars</label>
                                                <input type="radio" id="star1" name="rating" value="1"/>
                                                <label for="star1" title="text">1 star</label>
                                            </div>
                                            <div class="cmt-form">
                                                <div class="row">
                                                    <div class="col-xs-12 mg-bottom-30">
                                                            <textarea id="message" class="form-control"
                                                                      name="content" rows="9"
                                                                      placeholder="Đánh giá của bạn"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group text-center">
                                                    <button type="submit" class="zoa-btn btn btn-primary">
                                                        Xác nhận
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        <div
                                                class="alert alert-info alert-dismissible bg-danger text-white border-0 fade show"
                                                role="alert">
                                            <strong>Thông báo - </strong> Bạn cần đặt tour này để đánh giá!
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            @if(session('success'))
            $.notify('{{ session('success') }}', "success");
            @endif
            @if(session('error'))
            $.notify('{{ session('error') }}', "error");
            @endif
        });
    </script>
@endpush

