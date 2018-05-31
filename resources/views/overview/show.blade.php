@extends('adminlte::page')

@section('title', 'UNILAD Insights')

@section('content_header')
    <h1>{{$page->name}} Overview</h1>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-12 text-center">
            <a href="{{ request()->fullUrlWithQuery(["timeline"=> 'today'])  }}" class="btn btn-lg btn-primary {{ isset($timeline) && $timeline == 'today'? 'active' : '' }}">Today</a>
            <a href="{{ request()->fullUrlWithQuery(["timeline"=> 'week']) }}"  class="btn btn-lg btn-primary {{ isset($timeline) && $timeline == 'week'? 'active' : '' }} ">This Week</a>
            <a href="{{ request()->fullUrlWithQuery(["timeline"=> 'month'])  }}" class="btn btn-lg btn-primary {{ isset($timeline) && $timeline == 'month'? 'active' : '' }}">This Month</a>
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
                                    <span class="info-box-text">Total Video Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['reach'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Link Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['reach'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total IA Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['reach'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Reactions--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Video Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['reactions'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Link Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['reactions'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total IA Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['reactions'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Shares--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Video Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['shares'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Link Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['shares'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total IA Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['shares'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Comments--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Video Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['comments'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Link Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['comments'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total IA Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['comments'][$timeline][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Graph --}}
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> This {{$timeline == 'today'? 'Day': ucwords($timeline)}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                    <div>
                        <hr>
                        <div class="col-lg-12">
                            <div class="col-lg-12 col-lg-offset-5">
                                <div class="btn-toolbar">
                                    <div class="dropdown">
                                        <button class="btn btn-lg btn-default dropdown-toggle" type="button" data-toggle="dropdown">Viewing: {{ ucwords($metric) ?? 'Select Metric'}} <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ request()->fullUrlWithQuery(["metric"=> 'reach']) }}">Reach</a></li>
                                            <li><a href="{{ request()->fullUrlWithQuery(["metric"=> 'reactions']) }}">Reactions</a></li>
                                            <li><a href="{{ request()->fullUrlWithQuery(["metric"=> 'comments']) }}">Comments</a></li>
                                            <li><a href="{{ request()->fullUrlWithQuery(["metric"=> 'shares']) }}">Shares</a></li>
                                        </ul>
                                    </div>
                                    <div class="dropbox"></div>
                                    <div class="dropdown">
                                        <button class="btn btn-lg btn-default dropdown-toggle" type="button" data-toggle="dropdown">By: {{ ucwords($unit) ?? 'Select Unit'}} <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ request()->fullUrlWithQuery(["unit"=> 'hour']) }}">Hour</a></li>
                                            <li><a href="{{ request()->fullUrlWithQuery(["unit"=> 'day']) }}">Day</a></li>
                                            <li><a href="{{ request()->fullUrlWithQuery(["unit"=> 'week']) }}">Week</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <canvas id="lineChartCanvas" class="chart" style="padding:50px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    <script>
        $(function () {

            var areaChartOptions = {
                showScale: true,  //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: false,  //String - Colour of the grid lines
                scaleGridLineColor: 'rgba(0,0,0,.05)',  //Number - Width of the grid lines
                scaleGridLineWidth: 1,  //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,  //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,  //Boolean - Whether the line is curved between points
                bezierCurve: true,  //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,  //Boolean - Whether to show a dot for each point
                pointDot: true,  //Number - Radius of each point dot in pixels
                pointDotRadius: 4,  //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,  //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,  //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,  //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,  //Boolean - Whether to fill the dataset with a color
                datasetFill: true,  //String - A legend template
                maintainAspectRatio: true,  //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                title: {
                    display: false,
                    text: '{{ ucwords($metric) }} by the {{$timeline == 'today'? 'Day': ucwords($unit)}}',
                    fontSize: 25,
                }
            };

            var reachData = {
                labels: [
                    @foreach($videoStats['graph']['reach'][$timeline] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Total Reach (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph']['reach'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'Total Reach (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph']['reach'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'Total Reach (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph']['reach'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                ]
            };
            var reactionData = {
                labels: [
                    @foreach($videoStats['graph']['reactions'][$timeline] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Total Reach (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph']['reactions'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'Total Reach (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph']['reactions'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'Total Reach (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph']['reactions'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                ]
            };
            var sharesData = {
                labels: [
                    @foreach($videoStats['graph']['shares'][$timeline] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Total Reach (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph']['shares'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'Total Reach (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph']['shares'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'Total Reach (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph']['shares'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                ]
            };
            var commentsData = {
                labels: [
                    @foreach($videoStats['graph']['comments'][$timeline] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Total Comments (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph']['comments'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'Total Comments (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph']['comments'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'Total Comments (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph']['comments'][$timeline] as $key => $value)
                                '{{$value}}',
                            @endforeach
                        ]
                    },
                ]
            };

            var lineChartCanvas = $('#lineChartCanvas').get(0).getContext('2d');

            function drawChart(type)
            {
                var data;
                if(type == 'reach') data = reachData;
                if(type == 'reactions') data = reactionData;
                if(type == 'shares') data = sharesData;
                if(type == 'comments') data = commentsData;

                var graph_type = new Chart(lineChartCanvas, {
                    type: 'line',

                    data: data,
                    options: areaChartOptions,
                });
            }

            window.onload = function() {
                drawChart('{{$metric}}');
            }
        });
    </script>
@stop
