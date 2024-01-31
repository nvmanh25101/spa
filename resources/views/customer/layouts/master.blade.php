@include('customer.layouts.header')

<body class="loading" data-layout="topnav"
      data-layout-config='{"layoutBoxed":false,"darkMode":false,"showRightSidebarOnStart": true}'>
<div class="wrapper">
    <div class="content-page">
        <div class="content">
            @include('customer.layouts.navbar')
                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item">
                            <img class="d-block img-fluid" src="https://www.vietnambooking.com/wp-content/uploads/2023/01/tour-chao-thu-banner-09092023.png" alt="First slide">
                        </div>
                        <div class="carousel-item active">
                            <img class="d-block img-fluid" src="https://www.vietnambooking.com/wp-content/uploads/2020/02/Tour_khách_đoàn.jpg" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid" src="https://www.vietnambooking.com/wp-content/uploads/2022/12/dat-tour-mien-tay-14122022.png" alt="Third slide">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            <div class="container-fluid mt-4">
                <!-- start page title -->
                <div class="row master-row">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </div>
                <!-- end page title -->

            </div>
            <!-- container -->
        </div>
        <!-- content -->
@include('customer.layouts.footer')
