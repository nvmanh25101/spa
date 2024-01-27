@php use App\Enums\AppointmentStatusEnum;use App\Enums\OrderPaymentStatusEnum;use App\Enums\OrderStatusEnum;use Carbon\Carbon; @endphp
@extends('customer.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/customer/account.css') }}">
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/08/slide-1.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Tài khoản của tôi
            </h1>
        </div>
    </div>
@endsection
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                    <li class="nav-item">
                        <a href="#settings" data-toggle="tab" aria-expanded="false"
                           class="nav-link rounded-0 active">
                            Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#orders" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#appointments" data-toggle="tab" aria-expanded="false" class="nav-link rounded-0">
                            Lịch đặt
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="settings">
                        <form action="{{ route('account.update', auth()->user()) }}" method="post">
                            @csrf
                            @method('PATCH')
                            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i>Thông tin cá
                                nhân</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="firstname">Họ và tên</label>
                                        <input type="text" class="form-control" id="firstname" name="name"
                                               value="{{ $account->name }}"
                                               placeholder="Enter name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="useremail">Email</label>
                                        <input type="email" class="form-control" id="useremail" disabled
                                               value="{{ $account->email }}" placeholder="Enter email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="useremail">Số điện thoại</label>
                                        <input type="text" class="form-control" id="useremail" name="phone"
                                               value="{{ $account->phone }}" placeholder="Enter phone">
                                    </div>
                                </div>
                            </div> <!-- end row -->
                            <!-- end row -->
                            <h5 class="mb-3 text-uppercase bg-light p-2"><i
                                    class="mdi mdi-office-building mr-1"></i>Địa chỉ</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="companyname">Địa chỉ cụ thể</label>
                                        <input type="text" class="form-control" id="companyname" name="address"
                                               value="{{ $account->address }}"
                                               placeholder="Nhập địa chỉ cụ thể">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cwebsite">Quận/Huyện</label>
                                        <input type="text" class="form-control" id="cwebsite" name="district"
                                               value="{{ $account->district }}"
                                               placeholder="Nhập Quận/Huyện">
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cwebsite">Tỉnh/Thành phố</label>
                                        <input type="text" class="form-control" id="cwebsite" name="city"
                                               value="{{ $account->city }}"
                                               placeholder="Nhập Tỉnh/Thành phố">
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success mt-2"><i
                                        class="mdi mdi-content-save"></i> Cập nhật
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="orders">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày đặt hàng</th>
                                            <th>Trạng thái</th>
                                            <th>Thanh toán</th>
                                            <th>Tổng tiền</th>
                                            <th>Thao tác</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $key => $order)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $order->code }}</td>
                                                <td>{{ Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    @foreach(OrderStatusEnum::asArray() as $key => $value)
                                                        @php $class = '' @endphp
                                                        @if($value === OrderStatusEnum::CHO_XAC_NHAN)
                                                            @php $class = 'warning' @endphp
                                                        @elseif($value === OrderStatusEnum::CHO_LAY_HANG)
                                                            @php $class = 'primary' @endphp
                                                        @elseif($value === OrderStatusEnum::DANG_GIAO)
                                                            @php $class = 'info' @endphp
                                                        @elseif($value === OrderStatusEnum::DA_GIAO)
                                                            @php $class = 'success' @endphp
                                                        @elseif($value === OrderStatusEnum::DA_HUY)
                                                            @php $class = 'danger' @endphp
                                                        @elseif($value === OrderStatusEnum::HOAN_THANH)
                                                            @php $class = 'success' @endphp
                                                        @endif

                                                        @if($order->status === $value)
                                                            <span
                                                                class="badge badge-{{ $class }} p-1">{{ OrderStatusEnum::getKeyByValue($value) }}</span>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @if($order->payment_status === OrderPaymentStatusEnum::CHUA_THANH_TOAN)
                                                        <span class="badge badge-warning p-1">Chưa thanh toán</span>
                                                    @elseif($order->payment_status === OrderPaymentStatusEnum::DA_THANH_TOAN)
                                                        <span class="badge badge-success p-1">Đã thanh toán</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($order->total) }} đ</td>
                                                <td>
                                                    <a href="{{ route('orders.show', $order) }}"
                                                       class="btn btn-sm btn-primary">Xem chi tiết</a>
                                                    @if($order->status === OrderStatusEnum::CHO_XAC_NHAN)
                                                        <form action="{{ route('orders.destroy', $order) }}"
                                                              class="d-inline-block"
                                                              method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                    class="btn-delete btn btn-danger btn-sm">Hủy
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="float-right">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="appointments">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Mã lịch đặt</th>
                                            <th>Ngày đặt</th>
                                            <th>Trạng thái</th>
                                            <th>Tổng tiền</th>
                                            <th>Thao tác</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($reservations as $key => $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                                <td>
                                                    @foreach(AppointmentStatusEnum::asArray() as $key => $value)
                                                        @php $class = '' @endphp
                                                        @if($value === AppointmentStatusEnum::CHO_XAC_NHAN)
                                                            @php $class = 'warning' @endphp
                                                        @elseif($value === AppointmentStatusEnum::XAC_NHAN)
                                                            @php $class = 'primary' @endphp
                                                        @elseif($value === AppointmentStatusEnum::TU_CHOI)
                                                            @php $class = 'danger' @endphp
                                                        @endif

                                                        @if($item->status === $value)
                                                            <span
                                                                class="badge badge-{{ $class }} p-1">{{ AppointmentStatusEnum::getKeyByValue($value) }}</span>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($item->total_price) }} đ</td>
                                                <td>
                                                    <a href="{{ route('reservations.show', $item) }}"
                                                       class="btn btn-sm btn-primary">Xem chi
                                                        tiết</a>
                                                    @if($item->status === AppointmentStatusEnum::CHO_XAC_NHAN)
                                                        <form action="{{ route('reservations.destroy', $item) }}"
                                                              class="d-inline-block"
                                                              method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                    class="btn-delete btn btn-danger btn-sm">Hủy
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="float-right">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end settings content-->
                </div> <!-- end tab-content -->
            </div> <!-- end card body -->
        </div> <!-- end card -->
    </div>
@endsection
@push('js')
    <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/main_product.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '.btn-delete', function () {
                let form = $(this).parents('form');
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: "btn btn-success",
                        cancelButton: "btn btn-danger"
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: "Bạn có chắc chắn muốn xóa?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Đồng ý",
                    cancelButtonText: "Hủy",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            dataType: 'json',
                            data: form.serialize(),
                            success: function (res) {
                                swalWithBootstrapButtons.fire({
                                    title: "Thành công!",
                                    text: res['success'],
                                    icon: "success"
                                });
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            },
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire({
                            title: "Hủy!",
                            text: "An toàn :)",
                            icon: "error"
                        });
                    }
                });
            });

            @if(session('success'))
            $.notify('{{ session('success') }}', "success");
            @endif
            @if(session('error'))
            $.notify('{{ session('error') }}', "error");
            @endif
        });
    </script>
@endpush

