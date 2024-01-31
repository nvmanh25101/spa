@extends('admin.layouts.master')
@push('css')
    <link href="{{ asset('datatables/datatables.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="col-12 mt-4">
        <a href="{{ route('admin.services.create') }}" class="btn btn-outline-primary">Thêm mới</a>

        <table id="data-table" class="table table-hover dt-responsive nowrap w-100">
            <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($services as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td class="table-action">
                        <a href="{{ route('admin.services.edit', $item) }}" class="action-icon me-4"> <i class="mdi mdi-pencil"></i></a>
                        <form action="{{ route('admin.services.destroy', $item) }}" method="post" class="action-icon">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="action-icon btn"><i class='mdi mdi-delete'></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>
    {{--    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/r-2.5.0/rg-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sl-1.7.0/datatables.min.js"></script>--}}

    <script>
        $(document).ready(function () {
            @if(session('success'))
            $.notify('{{ session('success') }}', "success");
            @endif
            @if(session('error'))
            $.notify('{{ session('error') }}', "error");
            @endif
        });
    </script>
@endpush
