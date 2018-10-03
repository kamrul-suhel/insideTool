@extends('layouts.app')

@section('content_header')

    <div class="row">
        <div class="col-lg-12">
            <h1>
                <span>
                {{ $pageName }}
                </span>
                <span class="pull-right">
                @if ($from->format('Y-m-d') == $to->format('Y-m-d'))
                {{ $from->format('l, jS F Y') }}
                @else
                {{ $from->format('l, jS F Y') }} &dash; {{ $to->format('l, jS F Y') }}
                    @endif
                </span>
            </h1>
            <br><br><br><br>
            <form class="pull-right" style="margin-top:-70px;">
                <div class="input-group">
                    <input class="form-control input-lg" style="resize: none;" type="text" name="rangepicker"
                           autocomplete="off">
                    @if(\App\Post::where('posted', '<',  \Carbon\Carbon::now()->subDays(env('EXPORT_POSTED_LIMIT'))->endOfDay())->count() > 0)
                        <a href="{{ route('exports.export', ['from' => $from->format('Y-m-d H:i:s'), 'to' => $to->format('Y-m-d H:i:s')]) }}"
                           class="btn btn-lg btn-success input-group-addon"
                           style="background-color: #00a65a; color: white;">Export CSV</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@stop

@section('content')
    <div id="app">
        {{-- Article/Video Overview Stats --}}
        <div class="row">
            {{-- Videos --}}
            @if (!$type || $type == 'video')
                <div class="col-md-{{$type ? '12' : '6'}}">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Videos</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        @if(count($averages) > 0)
                            <div class="box-body">
                                <graph-metric id="video-reach-metric" color="aqua" fa-icon="eye"
                                              actual="{{ $videoReach }}"
                                              target="{{ $averages->get('daily_reach_video')->average * (($daysInRange - 1) + $day_percentage) }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                                <graph-metric id="video-reactions-metric" color="teal" fa-icon="thumbs-up"
                                              actual="{{ $videoReactions }}"
                                              target="{{ $averages->get('daily_reactions_video')->average * (($daysInRange - 1) + $day_percentage) }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                                <graph-metric id="video-comment-metric" color="yellow" fa-icon="comment"
                                              actual="{{ $videoComments }}"
                                              target="{{ $averages->get('daily_comments_video')->average * (($daysInRange - 1) + $day_percentage) }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                                <graph-metric id="video-shares-metric" color="green" fa-icon="share"
                                              actual="{{ $videoShares }}"
                                              target="{{ $averages->get('daily_shares_video')->average * (($daysInRange - 1) + $day_percentage) }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Articles--}}
            @if (!$type || $type == 'link')
                <div class="col-md-{{$type ? '12' : '6'}}">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Articles</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                            </div>
                        </div>
                        @if(count($averages) > 0)
                            <div class="box-body">
								<?php if ($daysInRange > 1) $multiplier = ($daysInRange - 1) + $day_percentage; else $multiplier = 1 ?>
                                <graph-metric id="article-reach-metric" color="aqua"
                                              fa-icon="eye"
                                              actual="{{ $articleReach }}"
                                              target="{{ $averages->get('daily_reach_article')->average * $multiplier }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                                <graph-metric id="article-reactions-metric" color="teal"
                                              fa-icon="thumbs-up"
                                              actual="{{ $articleReactions }}"
                                              target="{{ $averages->get('daily_reactions_article')->average * $multiplier }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                                <graph-metric id="article-comment-metric" color="yellow"
                                              fa-icon="comment"
                                              actual="{{ $articleComments }}"
                                              target="{{ $averages->get('daily_comments_article')->average * $multiplier }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                                <graph-metric id="article-shares-metric" color="green"
                                              fa-icon="share"
                                              actual="{{ $articleShares }}"
                                              target="{{ $averages->get('daily_shares_article')->average * $multiplier }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                                <graph-metric id="article-clicks-metric"
                                              color="purple"
                                              fa-icon="hand-pointer-o"
                                              actual="{{ $posts->sum(function ($post) { if ($post->type == 'link') { return $post->link_clicks; } return 0; }) }}"
                                              target="{{ $averages->get('daily_link_clicks')->average * $multiplier }}"
                                              day-percentage="{{ $day_percentage }}">
                                </graph-metric>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Overall Stats --}}
        <div class="row">
            <div class="col-md-6">
                <div class="info-box bg-teal">
                    <i class="info-box-icon"><i class="fa fa-eye"></i></i>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Reach</span>
                        <span class="info-box-number info-box-number-big">{{ number_format($articleReach + $videoReach) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box bg-green">
                    <i class="info-box-icon"><i class="fa fa-thumb-tack"></i></i>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Posts</span>
                        <span class="info-box-number info-box-number-big">{{ $totalPosts }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-purple">
                    <i class="info-box-icon"><i class="fa fa-play"></i></i>
                    <div class="info-box-content">
                        <span class="info-box-text">Videos</span>
                        <span class="info-box-number info-box-number-big">{{ $totalVideos }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-maroon">
                    <i class="info-box-icon"><i class="fa fa-list"></i></i>
                    <div class="info-box-content">
                        <span class="info-box-text">Articles</span>
                        <span class="info-box-number info-box-number-big">{{ $totalLinks }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-orange">
                    <i class="info-box-icon"><i class="fa fa-bolt"></i></i>
                    <div class="info-box-content">
                        <span class="info-box-text">IA</span>
                        <span class="info-box-number info-box-number-big"> {{ $totalIA }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Creator Filter --}}
        <div class="box box-primary">
            <div class="box-header clearfix post-header @if ($creatorFilter) creator-filter @endif">
                <h3 class="box-title">Posts</h3>
                <div class="box-tools pull-right">
                    @if ($creatorFilter)
                        <p>Showing posts by:
                            <i class="badge bg-red creator">
                                <a href="{{ route('pages.show', ['id' => $pageId, 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'),]) }}">{{ $creatorFilter->name }}
                                    <i class="fa fa-times"></i>
                                </a>
                                &nbsp;
                            </i>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Tabel Filters --}}
            @if(count($averages) > 0)
                <div class="box-body averages"
                     data-average-likes="{{ $averages->get('likes')->average }}"
                     data-average-comments="{{ $averages->get('comments')->average }}"
                     data-average-shares="{{ $averages->get('shares')->average }}">
                    <div class="col-lg-12">
                        <a style="border-radius: 0px; @if(request()->get('type') == null) text-decoration: underline; font:bolder @endif" class="col-lg-3 btn btn-lg btn-success" href="{{ route('pages.show', ['id' => $pageId, 'from' => request()->get('from'), 'to' => request()->get('to')]) }}">All</a>
                        <a style="border-radius: 0px; @if(request()->get('type') == 'video') text-decoration: underline; font:bolder @endif" class="col-lg-3 btn btn-lg bg-purple" href="{{ route('pages.show', ['id' => $pageId, 'ia' => false, 'creator' => request()->get('creator'),  'label' => request()->get('label'), 'day' => request()->get('day'), 'type' => 'video', 'from' => request()->get('from'), 'to' => request()->get('to')]) }}">Videos</a>
                        <a style="border-radius: 0px; @if(request()->get('type') == 'link') text-decoration: underline; font:bolder @endif" class="col-lg-3 btn btn-lg bg-maroon" href="{{ route('pages.show', ['id' => $pageId, 'ia' => false, 'creator' => request()->get('creator'),  'label' => request()->get('label'), 'day' => request()->get('day'), 'type' => 'link', 'from' => request()->get('from'), 'to' => request()->get('to')]) }}">Links</a>
                        <a style="border-radius: 0px; @if(request()->get('type') == 'ia') text-decoration: underline; font:bolder @endif" class="col-lg-3 btn btn-lg bg-yellow" href="{{ route('pages.show', ['id' => $pageId, 'ia' => true, 'creator' => request()->get('creator'), 'label' => request()->get('label'), 'day' => request()->get('day'), 'from' => request()->get('from'), 'to' => request()->get('to')]) }}">Instant Articles</a>
                    </div>
                    <br>
                    <hr>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                @foreach ($labels as $label)
                                    @if(!in_array($label, explode(',', $chosenLabels)))
                                        <a class="label label-default" style="margin: 2px;" href="{{ route('pages.show', [
                                        'id' => $pageId,
                                        'from' => request()->get('from'),
                                        'to' => request()->get('to'),
                                        'label' => is_null($chosenLabels) ? $label : $chosenLabels .','.$label,
                                        'ia' => request()->get('ia'),
                                        'creator' => request()->get('creator'),
                                        'day' => request()->get('day'),
                                        'type' => request()->get('type')
                                        ]) }}"> {{$label}} </a>
                                    @else
                                        <a class="label label-info" style="margin: 2px;"> {{ $label }} </a>
                                    @endif
                                @endforeach
                                <a class="label label-danger" style="margin: 2px;" href="{{ route('pages.show', [
                                    'id' => $pageId,
                                    'from' => request()->get('from'),
                                    'to' => request()->get('to'),
                                    'ia' => request()->get('ia'),
                                    'creator' => request()->get('creator'),
                                    'day' => request()->get('day'),
                                    'type' => request()->get('type')
                                    ]) }}"> Reset Labels </a>
                                </a>
                                <br>
                                <div class="col-lg-12">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <hr/>

            {{--Table--}}
            <table class="table table-striped table-condensed" id="posts-table">
                <thead>
                <tr>
                    <th class="dt-center"></th>
                    <th class="dt-center" data-orderable="false">Type</th>
                    <th class="dt-center">Posted by</th>
                    <th class="dt-center" data-orderable="false"><i class="fa fa-image"></i></th>
                    <th class="dt-center" data-orderable="false">Message</th>
                    <th class="dt-center"><i class="fa fa-eye"></i></th>
                    <th class="dt-center"><i class="fa fa-thumbs-up"></i></th>
                    <th class="dt-center"><i class="fa fa-comment"></i></th>
                    <th class="dt-center"><i class="fa fa-share"></i></th>
                    <th class="dt-center"><i class="fa fa-hand-pointer-o"></i></th>
                    <th class="dt-center" data-orderable="false">Updated At</th>
                </tr>
                </thead>
                <tbody>
                {{-- Totals --}}
                @if(count($averages) > 0)
                    <tr class="">
                        <td><b>Totals</b></td>
                        <td colspan="4"></td>
                        <td>
                            <i class="badge @if ($type == 'link' && ($posts->sum('reach') > $averages->get('daily_reach_article')->average)) bg-green @elseif ($type == 'link') bg-red @elseif ($type == 'video' && ($posts->sum('reach') > $averages->get('daily_reach_video')->average)) bg-green @elseif ($type == 'video') bg-red @elseif ($posts->sum('reach') > $averages->get('daily_reach')->average)) bg-green @else bg-red @endif">
                                {{ number_format($posts->sum('reach')) }}<br/>
                            </i>
                        </td>
                        <td>
                            <i class="badge @if ($posts->sum('likes') > $averages->get('daily_likes')->average)  bg-green @else bg-red @endif">
                                {{ number_format($posts->sum('likes')) }}<br/>
                            </i>
                        </td>
                        <td>
                            <i class="badge @if ($type == 'link' && ($posts->sum('comments') > $averages->get('daily_comments_article')->average)) bg-green @elseif ($type == 'link') bg-red @elseif ($type == 'video' && ($posts->sum('comments') > $averages->get('daily_comments_video')->average)) bg-green @elseif ($type == 'video') bg-red @elseif ($posts->sum('comments') > $averages->get('daily_comments')->average)) bg-green @else bg-red @endif">
                                {{ number_format($posts->sum('comments')) }}<br/>
                            </i>
                        </td>
                        <td>
                            <i class="badge @if ($type == 'link' && ($posts->sum('shares') > $averages->get('daily_shares_article')->average)) bg-green @elseif ($type == 'link') bg-red @elseif ($type == 'video' && ($posts->sum('shares') > $averages->get('daily_shares_video')->average)) bg-green @elseif ($type == 'video') bg-red @elseif ($posts->sum('shares') > $averages->get('daily_shares')->average)) bg-green @else bg-red @endif">
                                {{ number_format($posts->sum('shares')) }}<br/>
                            </i>
                        </td>
                        <td>
                            <i class="badge @if ($type == 'link' && ($posts->sum('shares') > $averages->get('daily_shares_article')->average)) bg-green @elseif ($type == 'link') bg-red @elseif ($type == 'video' && ($posts->sum('shares') > $averages->get('daily_shares_video')->average)) bg-green @elseif ($type == 'video') bg-red @elseif ($posts->sum('shares') > $averages->get('daily_shares')->average)) bg-green @else bg-red @endif">
                                {{ number_format($posts->sum('link_clicks')) }}<br/>
                            </i>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif

                {{--Posts --}}
                @foreach ($posts as $post)
                    <tr class="dt-center">
                        <td class="dt-center" @if (!is_null($post->deleted_at)) style="background-color: lightpink;" @else style="width:130px;" @endif>
                            <small>{{ date('D m @ H:i:s', strtotime($post->posted)) }}</small>
                            <br>
                            @if(!is_null($post->deleted_at))
                                <small>Del: {{ date('D m @ H:i:s', strtotime($post->deleted_at)) }}</small> <br>
                            @endif
                            <i>{{ human_since($post->posted) }}</i>
                        </td>

                        <td class="dt-center" style="font-size: 12pt;">
                            @if($post->type == 'video')
                                <div class="label label-default" style="background: #570f85; color:white;">
                                    <i style="min-width:20px;" class="fa fa-youtube-play"></i>
                                </div>
                            @elseif($post->instant_article)
                                <div class="label label-default" style="background: #ffaf2b; color:white;">
                                    <i style="min-width:20px;" class="fa fa-bolt"></i>
                                </div>
                            @else
                                <div class="label label-default" style="background: #d22334; color:white;">
                                    <i style="min-width:20px;" class="fa fa-link"></i>
                                </div>
                            @endif
                        </td>

                        <td class="dt-center">
                            <a href="{{ route('pages.show', [
                                    'id' => $pageId,
                                    'creator' => $post->creator->id ?? null,
                                    'ia' => request()->get('ia'),
                                    'day' => request()->get('day'),
                                    'label' => request()->get('label'),
                                    'type' => request()->get('type'),
                                    'from' => request()->get('from'),
                                    'to' => request()->get('to')]) }}">
                                {{ $post->creator->name ?? "Unknown" }}
                            </a>
                        </td>

                        <td class="dt-center">
                            <a href="/posts/{{ $post->id }}"><img src="{{ $post->picture }}" height="50"></a>
                        </td>

                        <td class="dt-center">
                            <a target="_blank" style="color:black;" href="{{ $post->link }}">{{ $post->name }}
                                <i class="fa fa-external-link"></i></a>
                            <br>
                            &quot;<a href="/posts/{{ $post->id }}">{{ $post->message }}</a>&quot;
                            <br>
                            @if($post->type == 'video' && $post->videoLabels->count() > 0)

                                @foreach($post->videoLabels->pluck('label') as $label)
                                    @if(!in_array($label, explode(',', $chosenLabels)))
                                        <a class="label label-default" style="margin: 2px;" href="{{ route('pages.show', [
                                        'id' => $pageId,
                                        'from' => request()->get('from'),
                                        'to' => request()->get('to'),
                                        'label' => is_null($chosenLabels) ? $label : $chosenLabels .','.$label,
                                        'ia' => request()->get('ia'),
                                        'creator' => request()->get('creator'),
                                        'day' => request()->get('day'),
                                        'type' => request()->get('type')
                                        ]) }}"> {{$label}} </a>
                                    @else
                                        <a class="label label-info" style="margin: 2px;"> {{ $label }} </a>
                                    @endif
                                @endforeach
                            @else
                                <span class="label label-danger">NO TAGS! Gimme some tags, <b>{{ explode(' ',$post->creator->name)[0] }}!</b></span>
                            @endif

                        </td>

                        <td class="dt-center" data-sort="{{ $post->reach }}">
                            <i class="badge @if (!$post->isUnderAverage('reach', true, $post->type)) bg-green @else bg-red @endif ">
                                {{ number_format($post->reach) }}
                            </i>
                            <br/>
                            @if (!$post->isUnderAverage('reach', true, $post->type))
                                <i class="text-success"><i class="fa fa-caret-up"></i></i>
                            @else
                                <i class="text-danger"><i class="fa fa-caret-down"></i></i>
                            @endif
                            {{ number_format($post->percentageFromTarget('reach', true, $type)) }}%
                            <br/>
                            <em><i class="fa fa-bullseye"></i>
                                <small>{{ number_format($post->getTarget('reach', true, $post->type)) }}</small>
                            </em>
                        </td>

                        <td class="dt-center" data-sort="{{ $post->likes }}">
                            <i class="badge @if (!$post->isUnderAverage('likes', true, $post->type)) bg-green @else bg-red @endif "> {{ number_format($post->likes) }}</i><br/>
                            @if (!$post->isUnderAverage('likes', true, $post->type))
                                <i class="text-success"><i class="fa fa-caret-up"></i></i>
                            @else
                                <i class="text-danger"><i class="fa fa-caret-down"></i></i>
                            @endif
                            {{ number_format($post->percentageFromTarget('likes', true, $post->type)) }}%
                            <br/>
                            <em><i class="fa fa-bullseye"></i>
                                <small>{{ number_format($post->getTarget('likes', true, $post->type)) }}</small>
                            </em>
                        </td>

                        <td class="dt-center" data-sort="{{ $post->comments }}">
                            <i class="badge @if (!$post->isUnderAverage('comments', true, $post->type)) bg-green @else bg-red @endif "> {{ number_format($post->comments) }} </i><br/>
                            @if (!$post->isUnderAverage('comments', true, $post->type))
                                <i class="text-success"><i class="fa fa-caret-up"></i></i>
                            @else
                                <i class="text-danger"><i class="fa fa-caret-down"></i></i>
                            @endif
                            {{ number_format($post->percentageFromTarget('comments', true, $post->type)) }}%
                            <br/>
                            <em><i class="fa fa-bullseye"></i>
                                <small>{{ number_format($post->getTarget('comments', true, $post->type)) }}</small>
                            </em>
                        </td>

                        <td class="dt-center" data-sort="{{ $post->shares }}">
                            <i class="badge @if (!$post->isUnderAverage('shares', true, $post->type)) bg-green @else bg-red @endif "> {{ number_format($post->shares) }}</i><br/>
                            @if (!$post->isUnderAverage('shares', true, $post->type))
                                <i class="text-success"><i class="fa fa-caret-up"></i></i>
                            @else
                                <i class="text-danger"><i class="fa fa-caret-down"></i></i>
                            @endif
                            {{ number_format($post->percentageFromTarget('shares', true, $post->type)) }}%
                            <br/>
                            <em><i class="fa fa-bullseye"></i>
                                <small>{{ number_format($post->getTarget('shares', true, $post->type)) }}</small>
                            </em>
                        </td>

                        <td class="dt-center" data-sort="{{ $post->link_clicks > 0 ? $post->link_clicks : $post->ga_page_views }}">
                            @if($post->type === 'video')

                            @else
                                <i class="badge @if (!$post->isUnderAverage('link_clicks', true)) bg-green @else bg-red @endif ">
                                    {{ $post->link_clicks > 0 ? number_format($post->link_clicks) : "GA: ".number_format($post->ga_page_views) }}
                                </i>
                                <br/>
                                @if (!$post->isUnderAverage('link_clicks', true))
                                    <i class="text-success"><i class="fa fa-caret-up"></i></i>
                                @else
                                    <i class="text-danger"><i class="fa fa-caret-down"></i></i>
                                @endif
                                {{ number_format($post->percentageFromTarget('link_clicks', true)) }}%
                                <br/>
                                <em><i class="fa fa-bullseye"></i>
                                    <small>{{ number_format($post->getTarget('link_clicks', true)) }}</small>
                                </em>
                            @endif
                        </td>
                        <td><small>{{ date('D m Y @ H:i:s', strtotime($post->updated_at)) }}</small></td>
                        <td>
                            @if($post->videoMonitizationStatSnapshot->count() > 0)
                                <a href="/posts/{{ $post->id }}" class="btn btn-xs btn-primary"><i class="fa fa-line-chart"></i></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-lg-12 text-center">
                    {{ $posts->appends(request()->except('page'))}}
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/dataTables.bootstrap.min.js"></script>

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css"/>

    <script>
        $('input[name="rangepicker"]').daterangepicker({
            maxDate: moment(),
            timePicker: true,
            timePicker24Hour: true,
            alwaysShowCalendars: true,
            startDate: '{{ $from->format('d-m-Y H:i') }}',
            endDate: '{{ $to->format('d-m-Y H:i') }}',
            locale: {
                format: 'DD/MM/YYYY H:mm',
                firstDay: 1,
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last Week': [moment().subtract(1, 'weeks').startOf('isoWeek'), moment().subtract(1, 'weeks').endOf('isoWeek')],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            }
        });

        $('input[name="rangepicker"]').on('apply.daterangepicker', function (ev, picker) {
            window.location.search += '&from=' + picker.startDate.format('DD-MM-YY-H-mm') + '&to=' + picker.endDate.format('DD-MM-YY-H-mm');
        });

        $('#posts-table').dataTable({
            "paging": false,
        });
    </script>

@endpush
