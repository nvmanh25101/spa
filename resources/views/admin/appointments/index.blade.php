@extends('admin.layouts.master')
@push('css')
    <link href="{{ asset('datatables/datatables.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="col-4 d-flex mb-1">
        <label>Trạng thái</label>
        <select class="form-control" id="select-status">
            <option value="-1">Tất cả</option>
            @foreach($arrAppointmentStatus as $key => $value)
                <option value="{{ $value }}">
                    {{ $key }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        {{-- <a href="{{ route('admin.appointments.create') }}" class="btn btn-outline-primary">Thêm mới</a> --}}

        <table id="data-table" class="table table-hover dt-responsive nowrap w-100">
            <thead>
            <tr>
                <th>#</th>
                <th>Tên khách hàng</th>
                <th>Số điện thoại</th>
                <th>Dịch vụ</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Sửa</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    {{--    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/r-2.5.0/rg-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sl-1.7.0/datatables.min.js"></script>--}}

    <script>
        $(document).ready(function () {
            let table = $('#data-table').DataTable({
                dom: 'BRSlrtip',
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.appointments.api') }}',
                "order": [[0, "desc"]],
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name_booker', name: 'name_booker'},
                    {data: 'phone_booker', name: 'phone_booker'},
                    {data: 'service_name', name: 'service_name'},
                    {data: 'datetime', name: 'datetime'},
                    {data: 'status', name: 'status'},
                    {
                        data: 'edit',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return `<a class="btn btn-primary" href="${data}"><i class='mdi mdi-pencil'></i></a>`;
                        }
                    },
                ]
            });

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
                                table.draw();
                            },
                            error: function () {
                                swalWithBootstrapButtons.fire({
                                    title: "Hủy!",
                                    text: 'Đã xảy ra lỗi!',
                                    icon: "error"
                                });
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

            $('#select-status').change(function () {
                let value = this.value;
                table.column(5).search(value).draw();
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
