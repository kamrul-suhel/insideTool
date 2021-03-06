@extends('layouts.app')

@section('content_header')
    <h1>Post Stats</h1>
@stop

@section('content')
    <div id="app" class="row" data-page="show" data-post-id="{{ $post->id }}">
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

                @if(isset($post->videoStatSnapshots()->orderBy('id', 'desc')->first()->total_video_retention_graph))
                    {{-- Retetion Graph --}}
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"> Video Retention (The X axis is split into 40 segments of the
                                    total video time ({{$post->length}} seconds))</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i></button>
                                </div>
                                <canvas id="videoRetentionGraphCanvas" class="chart" style="height:400px;"></canvas>
                            </div>
                        </div>
                    </div>
                @endif

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
                                        @if ($post->creator)
                                            <tr>
                                                <th>Posted by</th>
                                                <td>
                                                    <a href="{{ route('pages.show', ['id' => $post->page_id, 'creator' => $post->creator->id]) }}">{{ $post->creator->name }}</a>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Link</th>
                                            <td><a target="_blank" href="{{ $post->link }}">{{ $post->link }}</a></td>
                                        </tr>
                                        <tr>
                                            <th>Facebook</th>
                                            <td><a target="_blank"
                                                   href="https://facebook.com/{{ $post->page->facebook_id }}/posts/{{ $post->facebook_id }}">Link</a>
                                            </td>
                                        </tr>
                                        @if ($post->type == 'video')
                                            <tr>
                                                <th>Labels</th>
                                                <td>
                                                    @foreach ($post->videoLabels as $label)
                                                        <span class="badge bg-aqua video-label"><a
                                                                    href="{{ route('pages.show', ['id' => $post->page_id, 'label' => $label->id]) }}">{{$label->label}}</a></span>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endif

                                        @if(auth()->user()->role === 'admin')
                                            @if($post->videoMonitizationStatSnapshot->count() > 0)
                                                <tr>
                                                    <th>Ads Stats</th>
                                                @php $latestAdsStats = $post->videoMonetizationStatsSnapshotLatest->toArray() @endphp
                                                <tr>
                                                    <td>Post video ad break ad impressions</td>
                                                    <td>{{number_format($latestAdsStats[0]['post_video_ad_break_ad_impressions'])}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Post video ad break earnings</td>
                                                    <td>
                                                        ${{number_format($latestAdsStats[0]['post_video_ad_break_earnings']/100)}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Post video ad break ad cpm</td>
                                                    <td>{{number_format($latestAdsStats[0]['post_video_ad_break_ad_cpm'])}}</td>
                                                </tr>
                                                </tr>
                                            @endif
                                        @endif
                                    </table>
                                    @if(auth()->user()->role === 'admin')
                                        @if($post->videoMonitizationStatSnapshot->count() > 0)
                                            <div class="col-lg-12">
                                                <canvas id="monitizationGraphCanvas" class="chart"
                                                        style="height:400px;"></canvas>
                                            </div>
                                        @endif
                                    @endif
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
                            <span class="info-box-text"><abbr title="Likes per minute">LPM</abbr> (Lifetime)</span>
                            <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->likespm_lifetime > $averages->get("likes_perminute_{$post->type}_lifetime")->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                    ">
                                @if ($liveLatest->likespm_lifetime > $averages->get("likes_perminute_{$post->type}_lifetime")->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->likespm_lifetime) }} <em><sup><small>({{ $averages->get("likes_perminute_{$post->type}_lifetime")->average }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->likespm_lifetime) }}
                                    <em><sup><small>({{ $averages->get("likes_perminute_{$post->type}_lifetime")->average }}
                                                )</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-share"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><abbr title="Shares per minute">SPM</abbr>  (Lifetime)</span>
                            <span class="info-box-number info-box-number-big
                                @if ($liveLatest->sharespm_lifetime > $averages->get("shares_perminute_{$post->type}_lifetime")->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                    ">
                                @if ($liveLatest->sharespm_lifetime > $averages->get("shares_perminute_{$post->type}_lifetime")->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->sharespm_lifetime) }} <em><sup><small>({{ $averages->get("shares_perminute_{$post->type}_lifetime")->average }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->sharespm_lifetime) }}
                                    <em><sup><small>({{ $averages->get("shares_perminute_{$post->type}_lifetime")->average }}
                                                )</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-purple"><i class="fa fa-comment"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><abbr title="Comments per minute">CPM</abbr> (Lifetime)</span>
                            <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->commentspm_lifetime > $averages->get("comments_perminute_{$post->type}_lifetime")->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                    ">
                                @if ($liveLatest->commentspm_lifetime > $averages->get("comments_perminute_{$post->type}_lifetime")->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->commentspm_lifetime) }}
                                    <em><sup><small>({{ $averages->get("comments_perminute_{$post->type}_lifetime")->average }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->commentspm_lifetime) }}
                                    <em><sup><small>({{ $averages->get("comments_perminute_{$post->type}_lifetime")->average }}
                                                )</small></sup></em>
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
                            <span class="info-box-text"><abbr
                                        title="Likes per minute">LPM</abbr> (First 5 minutes)</span>
                            <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->likespm_birth > $averages->get("likes_perminute_{$post->type}_birth")->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                    ">
                                @if ($liveLatest->likespm_birth > $averages->get("likes_perminute_{$post->type}_birth")->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->likespm_birth) }} <em><sup><small>({{ $averages->get("likes_perminute_{$post->type}_birth")->average }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->likespm_birth) }} <em><sup><small>({{ $averages->get("likes_perminute_{$post->type}_birth")->average }}
                                                )</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-share"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><abbr
                                        title="Shares per minute">SPM</abbr> (First 5 minutes)</span>
                            <span class="info-box-number info-box-number-big
                                @if ($liveLatest->sharespm_birth > $averages->get("shares_perminute_{$post->type}_birth")->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                    ">
                                @if ($liveLatest->sharespm_birth > $averages->get("shares_perminute_{$post->type}_birth")->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->sharespm_birth) }} <em><sup><small>({{ $averages->get("shares_perminute_{$post->type}_birth")->average }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->sharespm_birth) }} <em><sup><small>({{ $averages->get("shares_perminute_{$post->type}_birth")->average }}
                                                )</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-purple"><i class="fa fa-comment"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><abbr
                                        title="Comments per minute">CPM</abbr> (First 5 minutes)</span>
                            <span class="info-box-number info-box-number-big 
                                @if ($liveLatest->commentspm_birth > $averages->get("comments_perminute_{$post->type}_birth")->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                    ">
                                @if ($liveLatest->commentspm_birth > $averages->get("comments_perminute_{$post->type}_birth")->average)
                                    <i class="fa fa-angle-up"></i> {{ round($liveLatest->commentspm_birth) }} <em><sup><small>({{ $averages->get("comments_perminute_{$post->type}_birth")->average }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ round($liveLatest->commentspm_birth) }}
                                    <em><sup><small>({{ $averages->get("comments_perminute_{$post->type}_birth")->average }}
                                                )</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-orange"><i class="fa fa-eye"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><abbr
                                        title="Impressions per minute">IPM</abbr> (Lifetime)</span>
                            <span class="info-box-number info-box-number-largeish 
                                    @if ($delayedLatest->reachpm_lifetime > $averages->get("impressions_perminute_{$post->type}_lifetime")->average)
                                    text-green
@else
                                    text-red
@endif
                                    ">
                                    @if ($delayedLatest->reachpm_lifetime > $averages->get("impressions_perminute_{$post->type}_lifetime")->average)
                                    <i class="fa fa-angle-up"></i> {{ number_format(round($delayedLatest->reachpm_lifetime)) }}
                                    <em><sup><small>({{ number_format($averages->get("impressions_perminute_{$post->type}_lifetime")->average) }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ number_format(round($delayedLatest->reachpm_lifetime)) }}
                                    <em><sup><small>({{ number_format($averages->get("impressions_perminute_{$post->type}_lifetime")->average) }}
                                                )</small></sup></em>
                                @endif
                                </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="fa fa-user-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><abbr title="Engagement per minute">EPM</abbr> (First 5 minutes)</span>
                            <span class="info-box-number info-box-number-big
                                @if ($liveLatest->reactionspm_birth > $averages->get("reactions_perminute_{$post->type}_birth")->average)
                                    text-green
                                @else
                                    text-red
                                @endif
                                    ">
                                @if ($liveLatest->reactionspm_birth > $averages->get("reactions_perminute_{$post->type}_birth")->average)
                                    <i class="fa fa-angle-up"></i> {{ number_format(round($liveLatest->reactionspm_birth)) }}
                                    <em><sup><small>({{ number_format($averages->get("reactions_perminute_{$post->type}_birth")->average) }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ number_format(round($liveLatest->reactionspm_birth)) }}
                                    <em><sup><small>({{ number_format($averages->get("reactions_perminute_{$post->type}_birth")->average) }}
                                                )</small></sup></em>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="fa fa-user-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><abbr title="Engagement per minute">EPM</abbr> (Lifetime)</span>
                            <span class="info-box-number info-box-number-big
                                @if ($liveLatest->reactionspm_lifetime > $averages->get("reactions_perminute_{$post->type}_lifetime")->average)
                                    text-green 
                                @else
                                    text-red 
                                @endif
                                    ">
                                @if ($liveLatest->reactionspm_lifetime > $averages->get("reactions_perminute_{$post->type}_lifetime")->average)
                                    <i class="fa fa-angle-up"></i> {{ number_format(round($liveLatest->reactionspm_lifetime)) }}
                                    <em><sup><small>({{ number_format($averages->get("reactions_perminute_{$post->type}_lifetime")->average) }}
                                                )</small></sup></em>
                                @else
                                    <i class="fa fa-angle-down"></i> {{ number_format(round($liveLatest->reactionspm_lifetime)) }}
                                    <em><sup><small>({{ number_format($averages->get("reactions_perminute_{$post->type}_lifetime")->average) }}
                                                )</small></sup></em>
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
                            <li class="active"><a href="#lcs" data-toggle="tab"><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;<i
                                            class="fa fa-share"></i>&nbsp;&nbsp;<i class="fa fa-comment"></i></a></li>
                            <li><a href="#reactions" data-toggle="tab"><i class="fa fa-heart"></i>&nbsp;&nbsp;<i
                                            class="fa fa-exclamation"></i>&nbsp;&nbsp;<i class="fa fa-hand-paper-o"></i>&nbsp;&nbsp;<i
                                            class="fa fa-frown-o"></i>&nbsp;&nbsp;<i class="fa fa-at"></i></a></li>
                            <li><a href="#views" data-toggle="tab"><i class="fa fa-eye"></i></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="lcs">
                                <h3>Likes, comments, and shares
                                    <small>Post lifetime</small>
                                </h3>
                                <div class="chart">
                                    <graph-view id="chart-lcs-birth" birth="" fields="likes,shares,comments" type="live"
                                                post-id="{{ $post->id }}"></graph-view>
                                </div>
                            </div>
                            <div class="tab-pane" id="reactions">
                                <h3>Reactions
                                    <small>Post lifetime</small>
                                </h3>
                                <div class="chart">
                                    <graph-view id="chart-reactions" fields="loves,wows,hahas,sads,angrys" type="live"
                                                birth="false" post-id="{{ $post->id }}"></graph-view>
                                </div>
                            </div>
                            <div class="tab-pane" id="views">
                                <h3>Views
                                    <small>Everyone</small>
                                </h3>
                                <div class="chart">
                                    <graph-view id="chart-views" birth="" fields="impressions,uniques" type="delayed"
                                                post-id="{{ $post->id }}"></graph-view>
                                </div>
                                <h3>Video Views
                                    <small>Everyone</small>
                                </h3>
                                <div class="chart">
                                    <graph-view id="chart-video-views" birth=""
                                                fields="total_video_views,total_video_views_autoplayed,total_video_views_clicked_to_play,total_video_complete_views"
                                                type="video" post-id="{{ $post->id }}"></graph-view>
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
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.likes | number_format }}</span>
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
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.shares | number_format}}</span>
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
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.comments | number_format }}</span>
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
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.loves | number_format }}</span>
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
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.wows | number_format }}</span>
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
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.hahas | number_format }}</span>
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
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.sads | number_format }}</span>
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
                            <span v-cloak class="info-box-number info-box-number-big">@{{ metrics.angrys | number_format }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($post->videoStatSnapshots as $vidSnapshot)
        @if($vidSnapshot->total_video_retention_graph)
            @php
                $stats = str_replace('[', '',$vidSnapshot->total_video_retention_graph);
                $stats = str_replace(']', '',$stats);
                $stats = explode(',', $stats);
                $increments = $post->length/40;

                $newArray = [];
                foreach($stats as $key => $value) {
                    $inc = ($key + 1) * $increments;
                    $newArray[ (string) $inc] = $value;
                }
            @endphp
        @endif
    @endforeach
@stop

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        let areaChartOptions = {
            title: {
                display: false,
                text: 'Video Retention',
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
    </script>
    @if($post->videoMonitizationStatSnapshot->count() > 0)

        @php $earningsArray = $post->VideoMonitizationStatSnapshot->pluck('post_video_ad_break_earnings', 'created_at')->toArray(); @endphp

        <script>
            let monitizationGraphData = {
                labels: [
                    @foreach($earningsArray as $key => $value)
                        '{{ date('d-m-y', strtotime($key)) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Daily Ad Break Earnings ($)',
                    borderWidth: 1,
                    data: [
                        @foreach($earningsArray as $stat)
                            '{{ $stat/100 }}',
                        @endforeach
                    ]
                }]

            };

            new Chart(monitizationGraphCanvas, {
                type: 'line',
                data: monitizationGraphData,
                options: areaChartOptions,
            });
        </script>
    @endif

    @if($post->videoStatSnapshots->count() > 0)
        <script>
            let videoRetentionGraphCanvas = $('#videoRetentionGraphCanvas').get(0).getContext('2d');
            let monitizationGraphCanvas = $('#monitizationGraphCanvas').get(0).getContext('2d');

                    @if(isset($newArray))
            let videoRetentionGraphData = {
                    labels: [
                        @foreach($newArray as $key => $value)
                            '{{ $key }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Retention',
                        backgroundColor: '#57d3ff',
                        borderWidth: 1,
                        data: [
                            @foreach($newArray as $stat)
                                '{{ $stat }}',
                            @endforeach
                        ]
                    }]

                };

            new Chart(videoRetentionGraphCanvas, {
                type: 'bar',
                data: videoRetentionGraphData,
                options: areaChartOptions,
            });
            @endif
        </script>
    @endif

@endpush

@section('js')
@stop
