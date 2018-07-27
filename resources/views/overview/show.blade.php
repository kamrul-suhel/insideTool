@extends('adminlte::page')

@section('title', 'UNILAD Insights')

@section('content_header')
    <h1>{{$page->name}} Overview</h1>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-12 text-center">

            <form class="pull-right">
                <div class="input-group">
                    <input class="form-control input-lg" style="resize: none;" type="text" name="rangepicker" autocomplete="off">
                    <a href="{{ route('overview.show', ['id' => $id]) }}" class="btn btn-lg btn-success input-group-addon" style="background-color: #00a65a; color: white;">Reset</a>
                </div>
                <hr>
            </form>
            <a href="{{ request()->fullUrlWithQuery(["conversion"=> 'sum']) }}" class="btn btn-lg btn-primary {{ isset($conversion) && $conversion == 'sum'? 'active' : '' }}">Total</a>
            <a href="{{ request()->fullUrlWithQuery(["conversion"=> 'avg']) }}"  class="btn btn-lg btn-primary {{ isset($conversion) && $conversion == 'avg'? 'active' : '' }} ">Average</a>
            <hr>
        </div>

        {{-- Today--}}
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overall Statistics between {{ date('dS M Y', strtotime($from)) }} and {{ date('dS M Y', strtotime($to)) }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        {{--Totals--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-upload"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats[0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-upload"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Non IA  </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats[0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-upload"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats[0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Reach--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['reach'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Link Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['reach'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA Reach </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['reach'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Reactions--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['reactions'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Link Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['reactions'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-thumbs-up"></i><small class="fa fa-plus-circle"></small></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA Reactions </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['reactions'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Shares--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['shares'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Link Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['shares'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-share"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA Shares </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['shares'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>

                        {{--Comments--}}
                        <div class="col-md-4">
                            <div class="info-box bg-purple">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Video Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($videoStats['comments'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-maroon">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} Link Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($linkStats['comments'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-orange">
                                <i class="info-box-icon"><i class="fa fa-comment"></i></i>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ $conversion == 'sum'? 'Total' : 'Average' }} IA Comments </span>
                                    <span class="info-box-number info-box-number-big">{{  number_format($iAStats['comments'][0][0] ?? 0)  }}</span>
                                </div>
                            </div>
                        </div>
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
                        {{--<button type="button" class="btn btn-primary download-canvas" data-canvas="videoTotalsCanvas"><i class="fa fa-download"></i></button>--}}
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
                    <canvas id="videoTotalsCanvas" class="chart"></canvas>
                </div>
            </div>
        </div>

        {{-- Video Graphs 24 Hours ago--}}
        <div class="col-lg-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> Video Label Stats - {{ date('dS M Y', strtotime($videoLabelCompareDateFrom)) }} and {{ date('dS M Y', strtotime($videoLabelCompareDateTo)) }}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        {{--<button type="button" class="btn btn-primary download-canvas" data-canvas="videoYesterdayTotalsCanvas"><i class="fa fa-download"></i></button>--}}
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-lg-12">
                        <div class="col-lg-12 col-lg-offset-5">
                            <div class="btn-toolbar">
                                <p>{{$videoMetric}}</p>
                            </div>
                        </div>
                    </div>
                    <canvas id="videoYesterdayTotalsCanvas" class="chart"></canvas>
                </div>
            </div>
        </div>

        {{-- IA non IA comparison --}}
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> IA vs Non IA Total for the Last Month</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        {{--<button type="button" class="btn btn-primary download-canvas" data-canvas="videoYesterdayTotalsCanvas"><i class="fa fa-download"></i></button>--}}
                    </div>
                    <canvas id="iaNonIaComparisonCanvas" class="chart" style="height:400px;"></canvas>
                </div>
            </div>
        </div>

        {{-- Graph --}}
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> Video, Link, and Instant Article Metrics </h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        {{--<button type="button" class="btn btn-primary download-canvas" data-canvas="lineChartCanvas"><i class="fa fa-download"></i></button>--}}
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

        {{-- Top Performers --}}
        {{--<div class="col-lg-12">--}}
            {{--<div class="box">--}}
                {{--<div class="box-header">--}}
                    {{--<h3 class="box-title"> Creator Metrics </h3>--}}
                    {{--<div class="box-tools pull-right">--}}
                        {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>--}}
                        {{--<button type="button" class="btn btn-primary download-canvas" data-canvas="lineChartCanvas"><i class="fa fa-download"></i></button>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="box-body">--}}
                    {{--<div class="row">--}}
                        {{--<canvas id="creatorStatsCanvas" class="chart" style="padding:50px;"></canvas>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}


    </div>

@stop

@section('js')
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

    <script>
        $('input[name="rangepicker"]').daterangepicker({
            maxDate: moment(),
            alwaysShowCalendars: true,
            startDate: '{{ $from->format('d/m/Y') }}',
            endDate: '{{ $to->format('d/m/Y') }}',
            locale: {
                format: 'DD/MM/YYYY',
                firstDay: 1,
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last Week': [moment().subtract(1, 'weeks').startOf('isoWeek'), moment().subtract(1, 'weeks').endOf('isoWeek')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        $('input[name="rangepicker"]').on('apply.daterangepicker', function (ev, picker) {
            window.location.search += '&from=' + picker.startDate.format('YYYY-MM-DD') + '&to=' + picker.endDate.format('YYYY-MM-DD');
        });

        $(function () {
            let areaChartOptions = {
                title: {
                    display: false,
                    text: '{{ ucwords($metric) }} by the {{0 == 'today'? 'Day': ucwords($unit)}}',
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
            let graphData = {
                labels: [
                    @foreach($videoStats['graph'][$metric][0] as $key => $value)
                        "[{{date('d-m-y - H:m', strtotime($key))}}]",
                    @endforeach
                ],
                datasets: [
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} {{ ucwords($metric) }} (Video)',
                        borderColor: 'purple',
                        data: [
                            @foreach($videoStats['graph'][$metric][0] as $key => $value)
                                '{{round($value)}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} {{ ucwords($metric) }} (Link)',
                        borderColor: '#d81b60',
                        data: [
                            @foreach($linkStats['graph'][$metric][0] as $key => $value)
                                '{{round($value)}}',
                            @endforeach
                        ]
                    },
                    {
                        label: '{{ $conversion == 'sum'? 'Total' : 'Average' }} {{ ucwords($metric) }} (Instant Article)',
                        borderColor: 'orange',
                        data: [
                            @foreach($iAStats['graph'][$metric][0] as $key => $value)
                                '{{round($value)}}',
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
            let videoYesterdayTotalData = {
                labels: [
                    @foreach($videoYesterdayLabelTotals as $total)
                        '{{ $total->label }} ({{ $total->video_total }})',
                    @endforeach
                ],
                datasets: [
                    {
                        backgroundColor: [
                            @foreach($videoYesterdayLabelTotals as $total)
                                "#777",
                            @endforeach
                        ],
                        label: 'Most Popular Videos by Label',
                        data: [
                            @foreach($videoYesterdayLabelTotals as $total)
                                '{{ $total->total }}',
                            @endforeach
                        ]
                    },
                ]
            };
            let iaNonIaComparisonData = {
                labels: [
                    @foreach($iaNonIaComparison['non_ia'] as $ia)
                        "[{{date('D m y', strtotime($ia->posted))}}]",
                    @endforeach

                ],
                datasets: [
                    {
                        label: 'Non IA Total',
                        backgroundColor: [
                            @foreach($iaNonIaComparison['non_ia'] as $ia)
                                "#D31955",
                            @endforeach
                        ],
                        data: [
                            @foreach($iaNonIaComparison['non_ia'] as $ia)
                                '{{$ia->total}}',
                            @endforeach
                        ]
                    },
                    {
                        label: 'IA Total',
                        backgroundColor: [
                            @foreach($iaNonIaComparison['ia'] as $ia)
                                "#FF791A",
                            @endforeach
                        ],
                        data: [
                            @foreach($iaNonIaComparison['ia'] as $ia)
                                '{{$ia->total}}',
                            @endforeach
                        ]
                    },
                ]
            };

            {{--let creatorStatsData = {--}}
                {{--labels: [--}}
                    {{--@foreach($creatorStats as $total)--}}
                        {{--'{{ $total->name }} ({{ $total->posts }} posts)',--}}
                    {{--@endforeach--}}
                {{--],--}}
                {{--datasets: [{--}}
                    {{--label: 'Reach',--}}
                    {{--backgroundColor: '#57d3ff',--}}
                    {{--borderWidth: 1,--}}
                    {{--data: [--}}
                        {{--@foreach($creatorStats as $total)--}}
                            {{--'{{ $total->reach }}',--}}
                        {{--@endforeach--}}
                    {{--]--}}
                {{--}, {--}}
                    {{--label: 'Reactions',--}}
                    {{--backgroundColor: '#29b698',--}}
                    {{--borderWidth: 1,--}}
                    {{--data: [--}}
                        {{--@foreach($creatorStats as $total)--}}
                            {{--'{{ $total->reactions }}',--}}
                        {{--@endforeach--}}
                    {{--]--}}
                {{--}, {--}}
                    {{--label: 'Shares',--}}
                    {{--backgroundColor: '#7358ee',--}}
                    {{--borderWidth: 1,--}}
                    {{--data: [--}}
                        {{--@foreach($creatorStats as $total)--}}
                            {{--'{{ $total->shares }}',--}}
                        {{--@endforeach--}}
                    {{--]--}}
                {{--}, {--}}
                    {{--label: 'Comments',--}}
                    {{--backgroundColor: '#eea559',--}}
                    {{--borderWidth: 1,--}}
                    {{--data: [--}}
                        {{--@foreach($creatorStats as $total)--}}
                            {{--'{{ $total->comments }}',--}}
                        {{--@endforeach--}}
                    {{--]--}}
                {{--}]--}}

            {{--};--}}

            let lineChartCanvas = $('#lineChartCanvas').get(0).getContext('2d');
            let videoTotalsCanvas = $('#videoTotalsCanvas').get(0).getContext('2d');
            let videoYesterdayTotalsCanvas = $('#videoYesterdayTotalsCanvas').get(0).getContext('2d');
            let iaNonIaComparisonCanvas = $('#iaNonIaComparisonCanvas').get(0).getContext('2d');
            // let creatorStatsCanvas = $('#creatorStatsCanvas').get(0).getContext('2d');

            new Chart(videoTotalsCanvas, {
                type: 'bar',
                data: videoTotalData,
                options: areaChartOptions,
            });
            new Chart(videoYesterdayTotalsCanvas, {
                type: 'bar',
                data: videoYesterdayTotalData,
                options: areaChartOptions,
            });

            new Chart(lineChartCanvas, {
                type: 'line',
                data: graphData,
                options: areaChartOptions,
            });

            new Chart(iaNonIaComparisonCanvas, {
                type: 'bar',
                data: iaNonIaComparisonData,
                options: areaChartOptions,
            });

            // new Chart(creatorStatsCanvas, {
            //     type: 'bar',
            //     data: creatorStatsData,
            //     options: areaChartOptions,
            // });


        });
    </script>
@stop
