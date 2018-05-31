@extends('adminlte::page')

@section('title', 'UNILAD Insights')

@section('content_header')
    <h1>{{$page->name}} Overview</h1>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-12 text-center">
            <a href="{{ request()->fullUrlWithQuery(["timeline"=> 'today'])  }}" class="btn  btn-primary {{ isset($timeline) && $timeline == 'today'? 'active' : '' }}">Today</a>
            <a href="{{ request()->fullUrlWithQuery(["timeline"=> 'week']) }}"  class="btn  btn-primary {{ isset($timeline) && $timeline == 'week'? 'active' : '' }} ">This Week</a>
            <a href="{{ request()->fullUrlWithQuery(["timeline"=> 'month'])  }}" class="btn  btn-primary {{ isset($timeline) && $timeline == 'month'? 'active' : '' }}">This Month</a>
            <a href="{{ request()->fullUrlWithQuery(["timeline"=> 'all_time'])  }}" class="btn  btn-primary {{ isset($timeline) && $timeline == 'all_time'? 'active' : '' }}">All Time</a>
            ||
            <a href="{{ request()->fullUrlWithQuery(["conversion"=> 'sum']) }}" class="btn  btn-primary {{ isset($conversion) && $conversion == 'sum'? 'active' : '' }}">Total</a>
            <a href="{{ request()->fullUrlWithQuery(["conversion"=> 'avg']) }}"  class="btn  btn-primary {{ isset($conversion) && $conversion == 'avg'? 'active' : '' }} ">Average</a>
            <hr>
        </div>

        {{-- Today--}}
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overall Statistics for the {{$timeline == 'today'? 'Day': ucwords($timeline)}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        {{-- Reach--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['reach'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Link Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['reach'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['reach'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Reactions--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['reactions'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Link Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['reactions'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['reactions'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Shares--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['shares'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Link Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['shares'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['shares'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Comments--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['comments'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Link Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['comments'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['comments'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graph --}}
        <div class="col-lg-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> This {{$timeline == 'today'? 'Day': ucwords($timeline)}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <hr>
                    <div class="col-lg-12">
                        <div class="col-lg-12 col-lg-offset-5">
                            <div class="btn-toolbar">
                                <div class="dropdown">
                                    <button class="btn  btn-default dropdown-toggle" type="button" data-toggle="dropdown">Viewing: {{ ucwords($metric) ?? 'Select Metric'}} <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ request()->fullUrlWithQuery(["metric"=> 'reach']) }}">Reach</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(["metric"=> 'reactions']) }}">Reactions</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(["metric"=> 'comments']) }}">Comments</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(["metric"=> 'shares']) }}">Shares</a></li>
                                    </ul>
                                </div>
                                <div class="dropbox"></div>
                                <div class="dropdown">
                                    <button class="btn  btn-default dropdown-toggle" type="button" data-toggle="dropdown">By: {{ ucwords($unit) ?? 'Select Unit'}} <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ request()->fullUrlWithQuery(["unit"=> 'hour']) }}">Hour</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(["unit"=> 'day']) }}">Day</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(["unit"=> 'week']) }}">Week</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <canvas id="lineChartCanvas" class="chart" style="padding:50px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Video Graphs--}}
        <div class="col-lg-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> Video Label Stats</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="col-lg-12 col-lg-offset-5">
                            <div class="btn-toolbar">
                                <div class="dropdown">
                                    <button class="btn  btn-default dropdown-toggle" type="button" data-toggle="dropdown">Viewing: {{ ucwords($videoMetric) ?? 'Select Metric'}} <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ request()->fullUrlWithQuery(["video_metric"=> 'reach']) }}">Reach</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(["video_metric"=> 'reactions']) }}">Reactions</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(["video_metric"=> 'comments']) }}">Comments</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(["video_metric"=> 'shares']) }}">Shares</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <canvas id="videoTotalsCanvas" class="chart" style="padding:50px;"></canvas>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    <script>
        $(function () {

            let areaChartOptions = {
                title: {
                    display: false,
                    text: '{{ ucwords($metric) }} by the {{$timeline == 'today'? 'Day': ucwords($unit)}}',
                    fontSize: 25,
                },
                scaleShowValues: true,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            autoSkip: false
                        }
                    }]
                }
            };
            let reachData = {
                labels: [
                    @foreach($videoStats['graph']['reach'][$timeline] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph']['reach'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph']['reach'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph']['reach'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                ]
            };
            let reactionData = {
                labels: [
                    @foreach($videoStats['graph']['reactions'][$timeline] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph']['reactions'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph']['reactions'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph']['reactions'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                ]
            };
            let sharesData = {
                labels: [
                    @foreach($videoStats['graph']['shares'][$timeline] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph']['shares'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph']['shares'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Reach (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph']['shares'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                ]
            };
            let commentsData = {
                labels: [
                    @foreach($videoStats['graph']['comments'][$timeline] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Comments (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph']['comments'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Comments (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph']['comments'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} Comments (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph']['comments'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                ]
            };
            let videoTotalData = {
                labels: [
                    @foreach($videoLabelTotals as $total)
                        '{{ $total->label }} ({{ $total->video_total }})',
                    @endforeach
                ],
                datasets: [
                    {
                        backgroundColor: [
                            @foreach($videoLabelTotals as $total)
                             "#777",
                            @endforeach
                        ],
                        label: 'Most Popular Videos by Label',
                        data: [
                            @foreach($videoLabelTotals as $total)
                                '{{ $total->total }}',
                            @endforeach
                        ]
                    },
                ]
            };

            let lineChartCanvas = $('#lineChartCanvas').get(0).getContext('2d');
            let videoTotalsCanvas = $('#videoTotalsCanvas').get(0).getContext('2d');

            function drawChart(type)
            {
                let data;
                if(type == 'reach')     data = reachData;
                if(type == 'reactions') data = reactionData;
                if(type == 'shares')    data = sharesData;
                if(type == 'comments')  data = commentsData;

                let graph = new Chart(lineChartCanvas, {
                    type: 'line',
                    data: data,
                    options: areaChartOptions,
                });
            };

            let videoTotalGraph = new Chart(videoTotalsCanvas, {
                type: 'bar',
                data: videoTotalData,
                options: areaChartOptions,
            });

            window.onload = function() {
                drawChart('{{$metric}}');
            }
        });
    </script>
@stop
