@extends('layouts.app')
@section('js_header')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/funnel3d.js"></script>
    <script src="https://code.highcharts.com/modules/cylinder.js"></script>

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

                    <div class="col-12 col-md-6 float-start">
                        <div class="card m-1">
                            <div class="card-header text-center">{{__('Your number of tasks')}}
                                (
                                <span class="total_task">{{$total}}</span>
                                )
                            </div>

                            <div class="card-body">
                                <div id="container1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 float-end">
                        <div class="card m-1">
                            <div class="card-header text-center">{{__('Your number of tasks')}}(<span class="total_task">{{$total}}</span>)</div>

                            <div class="card-body">
                                <div id="container2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 float-end">
                        <div class="card m-1">
                            <div class="card-header text-center">{{__('Your number of tasks')}}(<span class="total_task">{{$total}}</span>)</div>

                            <div class="card-body">
                                <div id="container3"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 float-end">
                        <div class="card m-1">
                            <div class="card-header text-center">{{__('Your number of tasks')}}(<span class="total_task">{{$total}}</span>)</div>

                            <div class="card-body">
                                <div id="container4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var SITEURL = "{{ url('/') }}";
    var user_id = {{Auth::id()}};
    document.addEventListener('DOMContentLoaded', function () {
        var container1 = Highcharts.chart('container1', {
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

        var container2 = Highcharts.chart('container2', {
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
                    return '<span style="text-align: center;font-weight:bold; color:'+ (this.point?this.point.color:'') +'">'+this.key + ': ' + this.y + '</span>';
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

        var container4 = Highcharts.chart('container4', {
            chart: {
                type: 'cylinder',
                options3d: {
                    enabled: true,
                    alpha: 15,
                    beta: 15,
                    depth: 50,
                    viewDistance: 25
                }
            },
            title: {
                text: ''
            },
            credits: {
                enabled: false
            },
            plotOptions: {
                series: {
                    depth: 25,
                    colorByPoint: true,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
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
            legend: {
                enabled: false
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
            series: [{
                name: "{{__('Status')}}",
                data: @json($task_data),
                showInLegend: false
            }]
        });

        @php
            usort($task_data, function ($item1, $item2) {
                                return $item2['y'] <=> $item1['y'];
                            })
        @endphp
        var container3 = Highcharts.chart('container3', {
            chart: {
                type: 'funnel3d',
                options3d: {
                    enabled: true,
                    alpha: 15,
                    depth: 20,
                    viewDistance: 50
                }
            },
            title: {
                align: 'center',
                text: ''
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
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b> ({point.y:,.0f})',
                        softConnector: true,
                        y: 25,
                        x : -30
                    },
                    center: ['40%', '50%'],
                    neckWidth: '30%',
                    neckHeight: '25%',
                    width: '100%'
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: "{{__('Status')}}",
                data: @json($task_data),
            }],

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        plotOptions: {
                            series: {
                                dataLabels: {
                                    inside: true
                                },
                                center: ['50%', '50%'],
                                width: '100%'
                            }
                        }
                    }
                }]
            }
        });

        var wsUri =  "ws://127.0.0.1:8081/dashboard-websocket";
        websocket_d = new WebSocket(wsUri);
        websocket_d.onopen = function (ev) { // connection is open
            websocket_d.onmessage = function (ev) {
                var data_json = ev.data;
                if(isJson(data_json)){
                    var data = JSON.parse(data_json);
                    container1.series[0].setData(data['tasks']);
                    // container1.viewData();
                    container2.series[0].setData(data['tasks']);
                    // container2.viewData();
                    container4.series[0].setData(data['tasks']);
                    // container4.viewData();
                    var byDate = data['tasks'].slice(0);
                    byDate.sort(function(a,b) {
                        return b.y - a.y ;
                    });

                    container3.series[0].setData(byDate);
                    // container3.viewData();
                    $('.total_task').text(data['total'])
                }
            };
            setInterval(function (){
                websocket_d.send('tasks_'+user_id);
            },3000)
        }
    });
    function isJson(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
</script>
@endsection
