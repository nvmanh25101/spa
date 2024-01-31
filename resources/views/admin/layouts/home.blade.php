@extends('admin.layouts.master')
@push('css')
    <link href="{{ asset('css/home_admin.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="row mt-4">
        <div class="col-6">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="mdi mdi-account-multiple widget-icon bg-success-lighten text-success"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Number of Customers">Khách hàng</h5>
                            <h3 class="mt-3 mb-3">{{ $customerCount }}</h3>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="mdi mdi-cart-plus widget-icon bg-danger-lighten text-danger"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Number of Orders">Đơn hàng của
                                tháng</h5>
                            <h3 class="mt-3 mb-3">{{ $orderCount }}</h3>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row -->

            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="mdi mdi-currency-usd widget-icon bg-info-lighten text-info"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Average Revenue">Doanh thu của
                                tháng</h5>
                            <h3 class="mt-3 mb-3">{{ number_format($revenue) }} đ</h3>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

        </div>
        </div>
        <div class="col-6">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="mdi mdi-panda widget-icon bg-success-lighten text-success"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Number of Customers">Tour</h5>
                            <h3 class="mt-3 mb-3">{{ $productCount }}</h3>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->

                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="mdi mdi-owl widget-icon bg-danger-lighten text-danger"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Number of Orders">Dịch vụ</h5>
                            <h3 class="mt-3 mb-3">{{ $serviceCount }}</h3>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row -->

            <div class="row">
                <div class="col-lg-6">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-right">
                                <i class="mdi mdi-paw widget-icon bg-info-lighten text-info"></i>
                            </div>
                            <h5 class="text-muted font-weight-normal mt-0" title="Average Revenue">Voucher</h5>
                            <h3 class="mt-3 mb-3">{{ $voucherCount }}</h3>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div> <!-- end row -->

        </div>
    </div>
@endsection
@push('js')
    @if (session('error'))
        <script>
            $.toast({
                heading: 'Thông báo',
                text: '{{ session('error') }}',
                icon: 'success',
                loader: true,
                loaderBg: 'rgba(0,0,0,0.2)',
                position: 'top-right',
                showHideTransition: 'slide',
            })
        </script>
    @endif
@endpush
