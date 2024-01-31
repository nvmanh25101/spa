<div class="left-side-menu">

    <!-- LOGO -->

    <div class="h-100" id="left-side-menu-container" data-simplebar>

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<div class="left-side-menu">

    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="assets/images/logo.png" alt="" height="16">
        </span>
    </a>

    <div class="h-100" id="left-side-menu-container" data-simplebar>

        <!--- Sidemenu -->
        <ul class="metismenu side-nav">

            <li class="side-nav-item">
                <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span>Dashboards</span>
                </a>
            </li>
            @if (Auth::guard('admin')->user()->role === \App\Enums\AdminType::QUAN_LY)
                <li class="side-nav-item">
                    <a href="{{ route('admin.employees.index') }}" class="side-nav-link" aria-expanded="false">
    {{--                        <i class="uil-store"></i>--}}
                        <span> Quản lý nhân viên </span>
                    </a>
                <li class="side-nav-item">
                    <a href="{{ route('admin.vouchers.index') }}" class="side-nav-link">
                        <span>Voucher</span>
                    </a>
                </li>
                <li class="side-nav-item">
                    <a href="{{ route('admin.statistic') }}" class="side-nav-link">
                        <span>Thống kê</span>
                    </a>
                </li>
            @endif
            <li class="side-nav-item">
                <a href="{{ route('admin.reservations.index') }}" class="side-nav-link">
                    <span>Đơn đặt tour</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.blogs.index') }}" class="side-nav-link">
                    <span>Blog</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.tours.index') }}" class="side-nav-link">
                    <span>Tour</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.services.index') }}" class="side-nav-link">
                    <span>Dịch vụ</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.destinations.index') }}" class="side-nav-link">
                    <span>Điểm đến</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.categories.index') }}" class="side-nav-link">
                    <span>Danh mục</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.customers.index') }}" class="side-nav-link">
                    <span>Khách hàng</span>
                </a>
            </li>
        </ul>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
