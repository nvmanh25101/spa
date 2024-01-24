@php use App\Enums\AdminType; @endphp
<div class="left-side-menu">

    <!-- LOGO -->
    <a href="index.html" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="assets/images/logo.png" alt="" height="16">
        </span>
        <span class="logo-sm">
            <img src="assets/images/logo_sm.png" alt="" height="16">
        </span>
    </a>

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

            <li class="side-nav-title side-nav-item">Navigation</li>

            <li class="side-nav-item">
                <a href="{{ route('admin.dashboard') }}" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span>Dashboards</span>
                </a>
            </li>
            @if (Auth::guard('admin')->user()->role === AdminType::QUAN_LY)
                <li class="side-nav-item">
                    <a href="javascript: void(0);" class="side-nav-link" aria-expanded="false">
                        {{--                        <i class="uil-store"></i>--}}
                        <span> Quản lý nhân viên </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="side-nav-second-level mm-collapse" aria-expanded="false" style="height: 0px;">
                        <li>
                            <a href="{{ route('admin.employees.index') }}">Nhân viên</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.employees.resign') }}">Nhân viên nghỉ việc</a>
                        </li>
                    </ul>
                </li>
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
                <a href="{{ route('admin.orders.index') }}" class="side-nav-link">
                    <span>Đơn hàng</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.appointments.index') }}" class="side-nav-link">
                    <span>Lịch đặt</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.blogs.index') }}" class="side-nav-link">
                    <span>Blog</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.products.index') }}" class="side-nav-link">
                    <span>Sản phẩm</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.services.index') }}" class="side-nav-link">
                    <span>Dịch vụ</span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('admin.categories.index') }}" class="side-nav-link">
                    <span>Danh mục</span>
                </a>
            </li>
        </ul>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
