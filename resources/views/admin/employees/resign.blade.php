@extends('admin.layouts.master')
@push('css')
    <link href="{{ asset('datatables/datatables.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="col-4 d-flex mb-1">
        <label>Chức vụ</label>
        <select class="form-control" id="select-level">
            <option value="-1">Tất cả</option>
            @foreach($arrAdminLevel as $key => $value)
                <option value="{{ $value }}">
                    {{ $key }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <table id="data-table" class="table table-hover table-centered mb-0 dt-responsive nowrap w-100">
            <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Địa chỉ</th>
                <th>Chức vụ</th>
            </tr>
            </thead>
        </table>
    </div>
@endsection
@push('js')
    <script src="{{ asset('datatables/datatables.min.js') }}"></script>
    {{--    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/r-2.5.0/rg-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sl-1.7.0/datatables.min.js"></script>--}}

    <script>
        $(document).ready(function () {
            let table = $('#data-table').DataTable({
                dom: 'BRSlrtip',
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.employees.resignList') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'address', name: 'address'},
                    {data: 'role', name: 'role'},
                ]
            });
            $('#select-level').change(function () {
                let value = this.value;
                table.column(5).search(value).draw();
            });
        });
    </script>
@endpush
