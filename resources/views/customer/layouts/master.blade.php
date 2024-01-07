@include('customer.layouts.header')

<body class="loading" data-layout="topnav"
      data-layout-config='{"layoutBoxed":false,"darkMode":false,"showRightSidebarOnStart": true}'>
<div class="wrapper">
    <div class="content-page">
        <div class="content">
            @include('customer.layouts.navbar')
            @yield('carousel')
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
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
