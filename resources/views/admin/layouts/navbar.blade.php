@php use App\Enums\AdminType;use App\Enums\NotiType; @endphp
<div class="navbar-custom">
    <ul class="list-unstyled topbar-right-menu float-right mb-0">
        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="false" aria-expanded="false">
                <i class="dripicons-bell noti-icon"></i>
                <span class="noti-icon-badge @if($arrNoti->isEmpty()) d-none @endif"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-lg">
                <!-- item-->
                <div class="dropdown-item noti-title">
                    <h5 class="m-0">
                        <span class="float-right">
{{--                             <a href="javascript: void(0);" class="text-dark">--}}
                            {{--                                <small>Xóa tất cả</small>--}}
                            {{--                             </a>--}}
                        </span>Thông báo
                    </h5>
                </div>

                <div style="max-height: 230px;" data-simplebar>
                    <!-- item-->
                    <div class="simplebar-wrapper" style="margin: 0px;">
                        <div class="simplebar-height-auto-observer-wrapper">
                            <div class="simplebar-height-auto-observer"></div>
                        </div>
                        <div class="simplebar-mask">
                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                <div class="simplebar-content-wrapper" style="height: auto; overflow: hidden scroll;">
                                    <div class="simplebar-content" style="padding: 0px;">
                                        @foreach($arrNoti as $noti)
                                            <a href="@if($noti->type == NotiType::DON_HANG)
                                                    {{ route('admin.orders.edit', $noti->object_id) }}
                                                @elseif($noti->type == NotiType::LICH)
                                                    {{ route('admin.appointments.edit', $noti->object_id) }}
                                                @endif
                                                "
                                               class="dropdown-item notify-item">
                                                <div class="notify-icon bg-primary">
                                                    <i class="mdi mdi-comment-account-outline"></i>
                                                </div>
                                                <p class="notify-details">{{ $noti->message }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simplebar-placeholder" style="width: auto; height: 389px;"></div>
                    </div>
                </div>
            </div>
        </li>

        <li class="dropdown notification-list">
            <a class="nav-link dropdown-toggle nav-user arrow-none mr-0" data-toggle="dropdown" href="#"
               role="button" aria-haspopup="false" aria-expanded="false">
                <span>
                    <span class="account-user-name">{{ Auth::guard('admin')->user()->name }}</span>
                    <span
                        class="account-position">{{ AdminType::getKeyByValue(Auth::guard('admin')->user()->role) }}</span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                <!-- item-->
                <div class=" dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Xin chào !</h6>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="mdi mdi-account-circle mr-1"></i>
                    <span>Tài khoản</span>
                </a>

                <!-- item-->
                <a href="{{ route("admin.logout") }}" class="dropdown-item notify-item">
                    <i class="mdi mdi-logout mr-1"></i>
                    <span>Đăng xuất</span>
                </a>

            </div>
        </li>
    </ul>
    <button class="button-menu-mobile open-left disable-btn">
        <i class="mdi mdi-menu"></i>
    </button>

</div>
