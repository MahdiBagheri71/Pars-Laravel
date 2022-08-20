@extends('layouts.app')
@section('js_header')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-6 float-start">
                        <div class="card m-1">
                            <div class="card-header text-center">{{__('Your number of tasks')}}({{$total}})</div>

                            <div class="card-body">
                                <div id="container1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 float-end">
                        <div class="card m-1">
                            <div class="card-header text-center">{{__('Your number of tasks')}}({{$total}})</div>

                            <div class="card-body">
                                <div id="container2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Highcharts.chart('container1', {
            chart: {
                type: 'column',
                options3d: {
                    enabled: true,
                    alpha: 20,
                    beta: 10
                }
            },
            title: {
                align: 'center',
                text: ''
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: '{{__("Total")}}'
                }
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },

            tooltip: {
                shared: true,
                useHTML: true,
                formatter: function()
                {

                    tooltip_html = "<table>";

                    this.points.forEach(function(entry)
                    {
                        tooltip_html += '<tr><td colspan="2" style="text-align: center;font-weight:bold; color:'+ entry.point.color +'">'+ entry.series.name +'</td></tr>' +
                            '<tr><td style="text-align: center; color:'+ entry.point.color +'"> '+entry.y+' : </td><td style="text-align: center;font-weight:bold; color:'+ entry.point.color +'">'+ entry.point.name +'</td></tr>';
                    });

                    tooltip_html += "</table>";

                    return tooltip_html;
                }
            },

            series: [
                {
                    name: "{{__('Status')}}",
                    colorByPoint: true,
                    data: @json($task_data)
                }
            ],
        });

        Highcharts.chart('container2', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    depth: 35,
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}'
                    }
                }
            },
            title: {
                align: 'center',
                text: ''
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true,
                useHTML: true,
                formatter: function() {
                    return '<span style="text-align: center;font-weight:bold; color:'+ this.point.color +'">'+this.key + ': ' + this.y + '</span>';
                }
            },
            series: [
                {
                    name: "{{__('Status')}}",
                    type: 'pie',
                    allowPointSelect: true,
                    data: @json($task_data),
                    showInLegend: true
                }
            ],
        });
    });
</script>
@endsection
