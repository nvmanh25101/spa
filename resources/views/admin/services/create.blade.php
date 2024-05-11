@extends('admin.layouts.master')
@push('css')
    <link href="{{ asset('css/base.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/services.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')
    <div class="col-12">
        <form method="post" action="{{ route('admin.services.store') }}" class="needs-validation" novalidate>
            @csrf
            <div class="form-group">
                <label>Tên</label>
                <input type="text" class="form-control" name="name" placeholder="Tên"
                       value="{{ old('name') }}" required>
            </div>

            <div class="form-group mb-3">
                <label>Mô tả</label>
                <textarea class="form-control" name="description" placeholder="Mô tả"
                          required>{{ old('description') }}</textarea>
            </div>

            <div class="row mb-1" id="duration_price">
                <div class="col-6 d-flex flex-row">
                    <div class="form-group">
                        <label for="duration">Thời lượng(phút)</label>
                        <input type="number" name="duration[]" id="duration" class=" form-control" min="1"/>
                    </div>

                    <div class="ms-1 form-group">
                        <label for="price">Giá tiền</label>
                        <div class="input-group">
                            <span class="input-group-text">VNĐ</span>
                            <input type="number" name="price[]" id="price" min="1"
                                   class="form__input form-control"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5 fs-4" id="add_price">
                <i class="mdi mdi-plus-circle-outline"></i>
            </div>
            <div class="row">
                <div class="form-group mb-3 col-8">
                    <label>Danh mục</label>
                    <select class="form-control" name="category_id">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button class="btn btn-primary mb-3" type="submit">Thêm</button>
        </form>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/notify.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    {{--    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/r-2.5.0/rg-1.4.1/sc-2.3.0/sb-1.6.0/sp-2.2.0/sl-1.7.0/datatables.min.js"></script>--}}

    <script>
        $(document).ready(function () {
            $('#add_price').click(function () {
                $('#duration_price').append(`
                <div class="col-6 d-flex flex-row">
                    <div class="form-group">
                        <label for="duration">Thời lượng(phút)</label>
                        <input type="number" name="duration[]" id="duration" class=" form-control"/>
                    </div>

                    <div class="ms-1 form-group">
                        <label for="price">Giá tiền</label>
                        <div class="input-group">
                            <span class="input-group-text">VNĐ</span>
                            <input type="number" name="price[]" id="price" min="1"
                                   class="form__input form-control"/>
                        </div>
                    </div>
                    <button type="button" class="delete-price">
                        <i class="mdi mdi-delete-outline"></i>
                    </button>
                </div>

                </div>
                `);
                $('.delete-price').click(function () {
                    $(this).parent().remove();
                });
            })


        })
    </script>
@endpush
