@extends('layouts.app')

@section('content_header')
    <h1>Post Stats</h1>
@stop

@section('content')
    <div id="app" class="row" >
        <div class="col-md-9">
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
                                <div class="col-sm-12">
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
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-thumbs-up"></i></span>
                        <div class="info-box-content">
                        <span class="info-box-text">LPM (Lifetime)</span>
                            <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->commentspm_lifetime > $averages->get('likes_perminute_lifetime')->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                ">
                                @if ($liveLatest->commentspm_lifetime > $averages->get('likes_perminute_lifetime')->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->commentspm_lifetime) }} <em><sup><small>({{ $averages->get('likes_perminute_lifetime')->average }})</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->commentspm_lifetime) }} <em><sup><small>({{ $averages->get('likes_perminute_lifetime')->average }})</small></sup></em>
                                @endif
                            </span>                        
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-share"></i></span>
                        <div class="info-box-content">
                        <span class="info-box-text">SPM (Lifetime)</span>
                        <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->commentspm_lifetime > $averages->get('shares_perminute_lifetime')->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                ">
                                @if ($liveLatest->commentspm_lifetime > $averages->get('shares_perminute_lifetime')->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->sharespm_lifetime) }} <em><sup><small>({{ $averages->get('shares_perminute_lifetime')->average }})</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->sharespm_lifetime) }} <em><sup><small>({{ $averages->get('shares_perminute_lifetime')->average }})</small></sup></em>
                                @endif
                            </span>                        
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-purple"><i class="fa fa-comment"></i></span>
                        <div class="info-box-content">
                        <span class="info-box-text">CPM (Lifetime)</span>
                            <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->commentspm_lifetime > $averages->get('comments_perminute_lifetime')->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                ">
                                @if ($liveLatest->commentspm_lifetime > $averages->get('comments_perminute_lifetime')->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->commentspm_lifetime) }} <em><sup><small>({{ $averages->get('comments_perminute_lifetime')->average }})</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->commentspm_lifetime) }} <em><sup><small>({{ $averages->get('comments_perminute_lifetime')->average }})</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-thumbs-up"></i></span>
                        <div class="info-box-content">
                        <span class="info-box-text">LPM (First 5 minutes)</span>
                            <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->likesspm_birth > $averages->get('likes_perminute_birth')->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                ">
                                @if ($liveLatest->likespm_birth > $averages->get('likes_perminute_birth')->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->likespm_birth) }} <em><sup><small>({{ $averages->get('likes_perminute_birth')->average }})</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->likespm_birth) }} <em><sup><small>({{ $averages->get('likes_perminute_birth')->average }})</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-share"></i></span>
                        <div class="info-box-content">
                        <span class="info-box-text">SPM (First 5 minutes)</span>
                        <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->commentspm_lifetime > $averages->get('shares_perminute_birth')->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                ">
                                @if ($liveLatest->likespm_birth > $averages->get('shares_perminute_birth')->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->sharespm_birth) }} <em><sup><small>({{ $averages->get('shares_perminute_birth')->average }})</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->sharespm_birth) }} <em><sup><small>({{ $averages->get('shares_perminute_birth')->average }})</small></sup></em>
                                @endif
                            </span>                        
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-purple"><i class="fa fa-comment"></i></span>
                        <div class="info-box-content">
                        <span class="info-box-text">CPM (First 5 minutes)</span>
                            <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->commentspm_lifetime > $averages->get('comments_perminute_birth')->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                ">
                                @if ($liveLatest->likespm_birth > $averages->get('comments_perminute_birth')->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->commentspm_birth) }} <em><sup><small>({{ $averages->get('comments_perminute_birth')->average }})</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->commentspm_birth) }} <em><sup><small>({{ $averages->get('comments_perminute_birth')->average }})</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#lcs" data-toggle="tab"><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;<i class="fa fa-share"></i>&nbsp;&nbsp;<i class="fa fa-comment"></i></a></li>
                            <li><a href="#reactions" data-toggle="tab"><i class="fa fa-heart"></i>&nbsp;&nbsp;<i class="fa fa-exclamation"></i>&nbsp;&nbsp;<i class="fa fa-hand-paper-o"></i>&nbsp;&nbsp;<i class="fa fa-frown-o"></i>&nbsp;&nbsp;<i class="fa fa-at"></i></a></li>
                            <li><a href="#views" data-toggle="tab"><i class="fa fa-eye"></i></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="lcs">
                                <h3>Likes, comments, and shares <small>First 5 minutes</small></h3>
                                <div class="chart">
                                    <graph-view id="chart-lcs" birth="true" fields="likes,shares,comments" type="live" post-id="{{ $post->id }}"></graph-view>
                                </div>
                                <h3>Likes, comments, and shares <small>Post lifetime</small></h3>
                                <div class="chart">
                                    <graph-view id="chart-lcs-birth" birth="" fields="likes,shares,comments" type="live" post-id="{{ $post->id }}"></graph-view>
                                </div>
                            </div>
                            <div class="tab-pane" id="reactions">
                                <h3>Reactions <small>First 5 minutes</small></h3>
                                <div class="chart">
                                    <graph-view id="chart-reactions-birth" fields="loves,wows,hahas,sads,angrys" type="live" birth="" post-id="{{ $post->id }}"></graph-view>
                                </div>
                                <h3>Reactions <small>Post lifetime</small></h3>
                                <div class="chart">
                                    <graph-view id="chart-reactions" fields="loves,wows,hahas,sads,angrys" type="live" birth="true" post-id="{{ $post->id }}"></graph-view>
                                </div>
                            </div>
                            <div class="tab-pane" id="views">
                                <h3>Views <small>Everyone</small></h3>
                                <div class="chart">
                                    <graph-view id="chart-views" birth="" fields="impressions,uniques" type="delayed" post-id="{{ $post->id }}"></graph-view>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" id="metrics">
            <div class="row">
                <div class="col-sm-12">
                    <div class="info-box bg-aqua">
                        <span class="info-box-icon"><i class="fa fa-thumbs-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Likes</span>
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.likes }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="info-box bg-green">
                        <span class="info-box-icon"><i class="fa fa-share"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Shares</span>
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.shares }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="info-box bg-purple">
                        <span class="info-box-icon"><i class="fa fa-comment"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Comments</span>
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.comments }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="info-box bg-maroon">
                        <span class="info-box-icon"><i class="fa fa-heart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Loves</span>
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.loves }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="info-box bg-yellow">
                        <span class="info-box-icon"><i class="fa fa-exclamation"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Wows</span>
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.wows }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="info-box bg-teal">
                        <span class="info-box-icon"><i class="fa fa-hand-paper-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Hahas</span>
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.hahas }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="info-box bg-blue">
                        <span class="info-box-icon"><i class="fa fa-frown-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Sads</span>
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.sads }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="info-box bg-red">
                        <span class="info-box-icon"><i class="fa fa-at"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Angrys</span>
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.angrys }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
    <!--script src="https://cdn.jsdelivr.net/npm/vue@2.5/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.1/dist/Chart.bundle.min.js"></script-->
@endpush

@section('js')
<script>

    // new Vue({
    //     el: '#metrics',
    //     data: {
    //         metrics: {
    //             likes: {{ $liveLatest->likes }},
    //             shares: {{ $liveLatest->shares }},
    //             comments: {{ $liveLatest->comments }},
    //             loves: {{ $liveLatest->loves }},
    //             wows: {{ $liveLatest->wows }},
    //             hahas: {{ $liveLatest->hahas}},
    //             sads: {{ $liveLatest->sads }},
    //             angrys: {{ $liveLatest->angrys }}
    //         }
    //     },
    //     methods: {
    //         loadData: function () {
    //             $.get('/posts/{{ $post->id }}/snapshots/latest/all', function (response) {
    //                 this.metrics = response;
    //             }.bind(this));
    //         }
    //     },
    //     mounted: function () {
    //         this.loadData();

    //         setInterval(function () {
    //             this.loadData();
    //         }.bind(this),1000); 
    //     }
    // });



    //jQuery(function() {

        // var lineOptions = {
        //     elements: {
        //         point: {
        //             radius: 0
        //         } 
        //     },
        //     scales: {
        //         xAxes: [{
        //             type: "time"
        //         }]
        //     },
        //     hover: {
        //         intersect: false
        //     },
        //     tooltips: {
        //         mode: 'index',
        //         intersect: false
        //     },
        //     legend: {
        //         labels: {
        //             fontStyle: 'bold'
        //         }
        //     }
        // };

        // var color = Chart.helpers.color;
        // $.ajax({
        //     url: "/posts/{{ $post->id }}/snapshots/live/all",
        //     method: 'GET',
        //     dataType: 'json',
        //     success: function (d) {
        //         var lcsctx = document.getElementById("chart-lcs").getContext("2d");
        //         var lcschart = new Chart(lcsctx, {
        //             'type' : 'line',
        //             'data' : {
        //                 datasets: [{
        //                     data: d.likes,
        //                     label: 'Likes',
        //                     backgroundColor: color("rgb(0, 192 ,239)").alpha(0.5).rgbString(),
        //                     borderColor: "rgb(0, 192, 239)",
        //                     borderWidth: 0,
        //                     fill: false
        //                 },
        //                 {
        //                     data: d.shares,
        //                     label: 'Shares',
        //                     backgroundColor: color("rgb(0, 166 ,90)").alpha(0.5).rgbString(),
        //                     borderColor: "rgb(0, 166, 90)",
        //                     borderWidth: 0,
        //                     fill: false
        //                 },
        //                 {
        //                     data: d.comments,
        //                     label: 'Comments',
        //                     backgroundColor: color("rgb(96, 92 ,168)").alpha(0.5).rgbString(),
        //                     borderColor: "rgb(96, 92, 168)",
        //                     borderWidth: 0,
        //                     fill: false
        //                 }]
        //             },
        //             'options' : lineOptions
        //         });
        //     }
        // });

    //             var reactionsctx = document.getElementById("chart-reactions").getContext("2d");
    //             var reactionschart = new Chart(reactionsctx, {
    //                 'type' : 'line',
    //                 'data' : {
    //                     datasets: [{
    //                         data: d.loves,
    //                         label: 'Loves',
    //                         backgroundColor: color("rgb(216, 27, 96)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(216, 27, 96)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.wows,
    //                         label: 'Wows',
    //                         backgroundColor: color("rgb(0, 166 ,90)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(0, 166, 90)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.hahas,
    //                         label: 'Hahas',
    //                         backgroundColor: color("rgb(57, 204 ,204)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(57, 204, 204)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.sads,
    //                         label: 'Sads',
    //                         backgroundColor: color("rgb(0, 185 ,183)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(0, 185, 183)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.angrys,
    //                         label: 'Angrys',
    //                         backgroundColor: color("rgb(221, 75, 157)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(221, 75, 157)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     }
    //                     ]
    //                 },
    //                 'options' : lineOptions
    //             });
    //         }
    //     });
    //     $.ajax({
    //         url: "/posts/{{ $post->id }}/snapshots/live/all/birth",
    //         method: 'GET',
    //         dataType: 'json',
    //         success: function (d) {
    //             var lcsctx = document.getElementById("chart-lcs-birth").getContext("2d");
    //             var lcschart = new Chart(lcsctx, {
    //                 'type' : 'line',
    //                 'data' : {
    //                     datasets: [{
    //                         data: d.likes,
    //                         label: 'Likes',
    //                         backgroundColor: color("rgb(0, 192 ,239)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(0, 192, 239)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.shares,
    //                         label: 'Shares',
    //                         backgroundColor: color("rgb(0, 166 ,90)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(0, 166, 90)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.comments,
    //                         label: 'Comments',
    //                         backgroundColor: color("rgb(96, 92 ,168)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(96, 92, 168)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     }]
    //                 },
    //                 'options' : lineOptions
    //             });

    //             var reactionsctx = document.getElementById("chart-reactions-birth").getContext("2d");
    //             var reactionschart = new Chart(reactionsctx, {
    //                 'type' : 'line',
    //                 'data' : {
    //                     datasets: [{
    //                         data: d.loves,
    //                         label: 'Loves',
    //                         backgroundColor: color("rgb(216, 27, 96)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(216, 27, 96)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.wows,
    //                         label: 'Wows',
    //                         backgroundColor: color("rgb(0, 166 ,90)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(0, 166, 90)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.hahas,
    //                         label: 'Hahas',
    //                         backgroundColor: color("rgb(57, 204 ,204)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(57, 204, 204)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.sads,
    //                         label: 'Sads',
    //                         backgroundColor: color("rgb(0, 185 ,183)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(0, 185, 183)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.angrys,
    //                         label: 'Angrys',
    //                         backgroundColor: color("rgb(221, 75, 157)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(221, 75, 157)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     }
    //                     ]
    //                 },
    //                 'options' : lineOptions
    //             });
    //         }
    //     });
    //     $.ajax({
    //         url: "/posts/{{ $post->id }}/snapshots/delayed/all",
    //         method: 'GET',
    //         dataType: 'json',
    //         success: function (d) {
    //             var viewsctx = document.getElementById("chart-views").getContext("2d");
    //             var viewschart = new Chart(viewsctx, {
    //                 'type' : 'line',
    //                 'data' : {
    //                     datasets: [{
    //                         data: d.impressions,
    //                         label: 'Impressions',
    //                         backgroundColor: color("rgb(216, 27, 96)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(216, 27, 96)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     },
    //                     {
    //                         data: d.uniques,
    //                         label: 'Uniques',
    //                         backgroundColor: color("rgb(0, 166 ,90)").alpha(0.5).rgbString(),
    //                         borderColor: "rgb(0, 166, 90)",
    //                         borderWidth: 0,
    //                         fill: false
    //                     }]
    //                 },
    //                 'options' : lineOptions
    //             });
    //         }
    //     });
    //});
    </script>
@stop
