@extends('admin.layouts.master')
@push('css')
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    <link href="{{ asset('datatables/datatables.min.css') }}" rel="stylesheet" type="text/css">
@endpush
@section('content')

    <div class="main__container">
        <select id="days" class="select-statistics form-control">
            <option>Chọn</option>
            <option value="7">7 ngày gần nhất</option>
            <option value="30">30 ngày gần nhất</option>
            <option value="-1">Theo tháng</option>
            <option value="-2">Theo năm</option>
        </select>
        <figure class="highcharts-figure">
            <div id="container"></div>
        </figure>
    </div>
@endsection
@push('js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script>
        $(document).ready(function () {
            $('#days').change(function () {
                const days = $(this).val();
                $.ajax({
                    url: '{{ route('admin.get_revenue') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        days: days
                    }
                })
                    .done(function (res) {
                        const arrX = Object.keys(res);
                        const arrY = Object.values(res);

                        Highcharts.chart('container', {

                            title: {
                                text: 'Thống kê doanh thu',
                                align: 'center'
                            },

                            yAxis: {
                                title: {
                                    text: 'Tổng tiền'
                                }
                            },

                            xAxis: {
                                categories: arrX
                            },

                            legend: {
                                layout: 'vertical',
                                align: 'right',
                                verticalAlign: 'middle'
                            },

                            plotOptions: {
                                series: {
                                    label: {
                                        connectorAllowed: false
                                    },
                                }
                            },

                            series: [{
                                name: 'Doanh thu',
                                data: arrY
                            }],

                            responsive: {
                                rules: [{
                                    condition: {
                                        maxWidth: 500
                                    },
                                    chartOptions: {
                                        legend: {
                                            layout: 'horizontal',
                                            align: 'center',
                                            verticalAlign: 'bottom'
                                        }
                                    }
                                }]
                            }
                        })
                    })
            });
        });
    </script>
@endpush
