@extends('layouts.app')

@section('content_header')
    <h1>Post Stats</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <img src="{{ $post->picture}}">
                                </div>
                                <div class="col-sm-7">
                                    <blockquote>{{ $post->message }}</blockquote>
                                    <p><em>{{ $post->name }}</em></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Info</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-10">
                                    <table class="table">
                                        <tr>
                                            <th>Posted</th>
                                            <td>{{ date("H:i, jS F Y", strtotime($post->posted)) }}</td>
                                        </tr>
                                        @if (!is_null($post->deleted_at))
                                            <tr class="error bg-red disabled">
                                                <th>Deleted</th>
                                                <td>{{ date("H:i, jS F Y", strtotime($post->deleted_at)) }}</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Page</th>
                                            <td>{{ $post->page->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td>{{ title_case($post->type) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Link</th>
                                            <td><a target="_blank" href="{{ $post->link }}">{{ $post->link }}</a></td>
                                        </tr>
                                        <tr>
                                            <th>Facebook</th>
                                            <td><a target="_blank" href="https://facebook.com/{{ $post->page->facebook_id }}/posts/{{ $post->facebook_id }}">Link</a></td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Charts</h3>
                        </div>
                        <div class="box-body charts">
                            <div class="row">
                                <div class="col-sm-12 chart-col">
                                    <div class="chart">
                                        <canvas id="chart-lcs" width="650" height="250"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-10">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><i class="fa fa-thumbs-o-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Likes</span>
                            <span class="info-box-number info-box-number-big">{{ number_format($liveLatest->likes) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <div class="info-box bg-green">
                        <span class="info-box-icon"><i class="fa fa-share"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Shares</span>
                            <span class="info-box-number info-box-number-big">{{ number_format($liveLatest->shares) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <div class="info-box bg-purple">
                        <span class="info-box-icon"><i class="fa fa-comment"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Comments</span>
                            <span class="info-box-number info-box-number-big">{{ number_format($liveLatest->comments) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="info-box bg-maroon">
                        <span class="info-box-icon"><i class="fa fa-heart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Loves</span>
                            <span class="info-box-number info-box-number-big">{{ number_format($liveLatest->loves) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <div class="info-box bg-yellow">
                        <span class="info-box-icon"><i class="fa fa-exclamation"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Wows</span>
                            <span class="info-box-number info-box-number-big">{{ number_format($liveLatest->wows) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <div class="info-box bg-teal">
                        <span class="info-box-icon"><i class="fa fa-hand-paper-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Hahas</span>
                            <span class="info-box-number info-box-number-big">{{ number_format($liveLatest->hahas) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <div class="info-box bg-blue">
                        <span class="info-box-icon"><i class="fa fa-frown-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sads</span>
                            <span class="info-box-number info-box-number-big">{{ number_format($liveLatest->sads) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <div class="info-box bg-red">
                        <span class="info-box-icon"><i class="fa fa-at"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Angrys</span>
                            <span class="info-box-number info-box-number-big">{{ number_format($liveLatest->angrys) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        
    </div>
@stop

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
@endpush

@section('js')
<script>
    jQuery(function() {

        var color = Chart.helpers.color;
        $.ajax({
            url: "/posts/{{ $post->id }}/snapshots/live/all",
            method: 'GET',
            dataType: 'json',
            success: function (d) {
                var ctx = document.getElementById("chart-lcs").getContext("2d");
                var chart = new Chart(ctx, {
                    'type' : 'line',
                    'data' : {
                        datasets: [{
                            data: d.likes,
                            label: 'Likes',
                            backgroundColor: color("rgb(0, 192 ,239)").alpha(0.5).rgbString(),
                            borderColor: "rgb(0, 192, 239)",
                            borderWidth: 0,
                            fill: false
                        },
                        {
                            data: d.shares,
                            label: 'Shares',
                            backgroundColor: color("rgb(0, 166 ,90)").alpha(0.5).rgbString(),
                            borderColor: "rgb(0, 166, 90)",
                            borderWidth: 0,
                            fill: false
                        },
                        {
                            data: d.comments,
                            label: 'Comments',
                            backgroundColor: color("rgb(96, 92 ,168)").alpha(0.5).rgbString(),
                            borderColor: "rgb(96, 92, 168)",
                            borderWidth: 0,
                            fill: false
                        }]
                    },
                    'options' : {
                        elements: {
                            point: {
                                radius: 0
                            } 
                        },
                        scales: {
                            xAxes: [{
                                type: "time"
                            }]
                        },
                        hover: {
                            intersect: false
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                });
        }
    });

    });
    </script>
@stop
