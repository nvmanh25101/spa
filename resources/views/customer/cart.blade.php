@php use App\Enums\ProductStatusEnum; @endphp
@extends('customer.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/customer/cart.css') }}">
@endpush
@section('carousel')
    <div class="text-center text-white d-flex align-items-center position-relative page-header"
         style="background-image: url(https://laspas.vn/ma-may/wp-content/uploads/sites/5/2018/08/slide-1.jpg);">
        <div class="m-auto">
            <h1 class="font-family-secondary h2 text-uppercase text-center mt-2 mb-3 page-title">
                Giỏ hàng của bạn
            </h1>
        </div>
    </div>
@endsection
@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng tiền</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if ($cart->products)
                                    @foreach ($cart->products as $item)
                                        @if($item->status == ProductStatusEnum::NGUNG_HOAT_DONG)
                                            @continue
                                        @endif
                                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                                        <tr class="product">
                                            <td>
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="contact-img"
                                                     title="contact-img" class="rounded mr-3" height="64">
                                                <p class="m-0 d-inline-block align-middle product-name">
                                                    <a href="{{ route('customers.product', $item) }}"
                                                       class="text-body">{{ $item->name }}</a>
                                                </p>
                                            </td>
                                            <td>
                                                <span class="price_unit">{{ $item->price }} đ</span>
                                            </td>
                                            <td>
                                                <input type="number" min="1"
                                                       value="{{ $item->pivot->quantity }}" max="{{ $item->quantity }}"
                                                       class="form-control quantity" placeholder="Qty"
                                                       style="width: 90px;">
                                                @method('put')
                                            </td>
                                            <td>
                                                    <span class="price">{{ $item->price * $item->pivot->quantity }}
                                                        đ</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('cart.destroy', $item->pivot->cart_id) }}"
                                                      method="post">
                                                    @method('delete')
                                                    @csrf
                                                    <input type="hidden" name="product_id"
                                                           value="{{ $item->pivot->product_id }}">
                                                    <button type="submit" class="action-icon btn"><i
                                                            class="mdi mdi-delete"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>
                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade d-none"
                                 role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <strong>Lỗi - </strong>
                            </div>
                        </div> <!-- end table-responsive-->

                        <!-- action buttons-->
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <a href="{{ route('customers.products') }}"
                                   class="btn text-muted d-none d-sm-inline-block btn-link font-weight-semibold">
                                    <i class="mdi mdi-arrow-left"></i> Tiếp tục mua sắm </a>
                            </div> <!-- end col -->
                            <div class="col-sm-6">
                                <div class="text-sm-right">
                                    <a href="{{ route('orders.index') }}" class="btn submit">
                                        <i class="mdi mdi-cart-plus mr-1"></i> Đặt hàng </a>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </div>
                    <!-- end col -->

                    <div class="col-lg-4">
                        <div class="border p-3 mt-4 mt-lg-0 rounded">
                            <h4 class="header-title mb-3">Tóm tắt giỏ hàng</h4>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <tbody>
                                    <tr>
                                        <td>Phí vận chuyển :</td>
                                        <td>Miễn phí</td>
                                    </tr>
                                    <tr>
                                        <th>Tổng tiền :</th>
                                        <th><span id="totalPrice"></span></th>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- end table-responsive -->
                        </div>
                    </div> <!-- end col -->

                </div> <!-- end row -->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
@endsection
@push('js')
    <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            updateTotalPrice();

            $(".quantity").on("input", function () {
                updatePrice($(this));
                updateTotalPrice();

                let quantity = $(this).val();
                let product_id = $(this).closest(".product").find("input[name='product_id']").val();
                let method = $(this).closest(".product").find("input[name='_method']").val();
                let alert_danger = $(".alert-danger");
                alert_danger.removeClass("show");
                alert_danger.addClass("d-none");
                alert_danger.empty();
                $.ajax({
                    url: "{{ route('cart.update', $cart->id) }}",
                    method: "PUT",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: method,
                        product_id,
                        quantity,
                    },
                    success: function (response) {
                        alert_danger.removeClass("d-none");
                        alert_danger.addClass("show");
                        alert_danger.append(response.error);

                    }
                });
            });

            function updatePrice(element) {
                let quantity = element.val();
                let pricePerUnit = parseFloat(element.closest(".product").find(".price_unit").text());
                let totalPrice = quantity * pricePerUnit;
                let formattedPrice = totalPrice.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
                element.closest(".product").find(".price").text(formattedPrice);
            }

            function updateTotalPrice() {
                let total = 0;

                $(".product").each(function () {
                    let price = parseFloat($(this).find(".price").text().replace(/[^\d]/g, ''));
                    total += price;
                });
                let formattedPrice = total.toLocaleString('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                });
                $("#totalPrice").text(formattedPrice);
            }

            @if (session('success'))
            $.notify('{{ session('success') }}', "success");
            @endif
            @if (session('error'))
            $.notify('{{ session('error') }}', "error");
            @endif
        });
    </script>
@endpush
