@extends('layouts.app')

@section('content_header')
    @if ($from->format('Y-m-d') == $to->format('Y-m-d'))
        <h1>{{ $from->format('l, jS F Y') }}</h1>
    @else
        <h1>{{ $from->format('l, jS F Y') }} &dash; {{ $to->format('l, jS F Y') }}</h1>
    @endif
@stop

@section('content')
<div id="app">
    <?php $day_percentage = $to->isToday() ? ((date('H') + (($daysInRange - 1) * 24)) / (24 + (($daysInRange - 1) * 24)) + date('i') / (60 * 24)) : 1;?>
    <div class="row">
        @if (!$type || $type == 'video')
            @if ($type)
            <div class="col-md-12">
            @else
            <div class="col-md-6">
            @endif
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Videos</h3>
                    </div>
                    @if(count($averages) > 0)
                        <div class="box-body">
                            <graph-metric id="video-reach-metric" color="aqua" fa-icon="eye" actual="<?php echo $videoReach; ?>" target="<?php echo $averages->get('daily_reach_video')->average * (($daysInRange - 1) + $day_percentage); ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                            <graph-metric id="video-reactions-metric" color="teal" fa-icon="thumbs-up" actual="<?php echo $videoReactions; ?>" target="<?php echo $averages->get('daily_reactions_video')->average * (($daysInRange - 1) + $day_percentage); ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                            <graph-metric id="video-comment-metric" color="yellow" fa-icon="comment" actual="<?php echo $videoComments; ?>" target="<?php echo $averages->get('daily_comments_video')->average * (($daysInRange - 1) + $day_percentage); ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                            <graph-metric id="video-shares-metric" color="green" fa-icon="share" actual="<?php echo $videoShares; ?>" target="<?php echo $averages->get('daily_shares_video')->average * (($daysInRange - 1) + $day_percentage); ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if (!$type || $type == 'link')
            @if ($type)
            <div class="col-md-12">
            @else
            <div class="col-md-6">
            @endif
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Articles</h3>
                    </div>

                    @if(count($averages) > 0)
                        <div class="box-body">
                            <?php if ($daysInRange > 1) {$multiplier = ($daysInRange - 1) + $day_percentage;} else {$multiplier = 1;} ?>
                            <graph-metric id="article-reach-metric" color="aqua" fa-icon="eye" actual="<?php echo $articleReach; ?>" target="<?php echo $averages->get('daily_reach_article')->average * $multiplier; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                            <graph-metric id="article-reactions-metric" color="teal" fa-icon="thumbs-up" actual="<?php echo $articleReactions; ?>" target="<?php echo $averages->get('daily_reactions_article')->average * $multiplier; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                            <graph-metric id="article-comment-metric" color="yellow" fa-icon="comment" actual="<?php echo $articleComments; ?>" target="<?php echo $averages->get('daily_comments_article')->average * $multiplier; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                            <graph-metric id="article-shares-metric" color="green" fa-icon="share" actual="<?php echo $articleShares; ?>" target="<?php echo $averages->get('daily_shares_article')->average * $multiplier; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                            <graph-metric id="article-clicks-metric" color="purple" fa-icon="hand-pointer-o" actual="{{ $posts->sum(function ($post) {
                                        if ($post->type == 'link') {
                                            return $post->link_clicks;
                                        }
                                        return 0;
                                        }) }}" target="<?php echo $averages->get('daily_link_clicks')->average * $multiplier; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="info-box bg-teal">
                <span class="info-box-icon"><i class="fa fa-eye"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Reach</span>
                    <span class="info-box-number info-box-number-big">
                        {{ number_format($articleReach + $videoReach) }}
                    </span>             
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="fa fa-thumb-tack"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Posts</span>
                    <span class="info-box-number info-box-number-big">
                        {{ count($posts) }}
                    </span>             
                </div>
            </div>
        </div>

        </div>
        <div class="row">

            <div class="col-md-4">
                <div class="info-box bg-purple">
                    <span class="info-box-icon"><i class="fa fa-play"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Videos</span>
                        <span class="info-box-number info-box-number-big">
                            {{ count($posts->where('type', 'video')) }}
                        </span>             
                    </div>
                </div>
            </div>

        <div class="col-md-4">
            <div class="info-box bg-maroon">
                <span class="info-box-icon"><i class="fa fa-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Articles</span>
                    <span class="info-box-number info-box-number-big">
                        {{ count($posts->where('type', 'link'))-count($posts->where('instant_article', true)) }}
                    </span>             
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box bg-orange">
                <span class="info-box-icon"><i class="fa fa-bolt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">IA</span>
                    <span class="info-box-number info-box-number-big">
                        {{ count($posts->where('instant_article', true)) }}
                    </span>             
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary">
        <div class="box-header clearfix post-header
            @if ($creatorFilter)
                creator-filter
            @endif
        ">
            <h3 class="box-title">Posts</h3>
            <div class="box-tools pull-right">
                @if ($creatorFilter)
                    <p>Showing posts by: <span class="badge bg-red creator"><a href="{{ route('posts.index', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d'),]) }}">{{ $creatorFilter->name }} <i class="fa fa-times"></i></a>&nbsp;</p>
                @endif
                <form>
                    <label for="rangepicker">Date range: <input class="form-control input-xlg" type="text" name="rangepicker"></label>
                </form>
            </div>
        </div>
        @if(count($averages) > 0)
            <div class="box-body averages" data-average-likes="{{ $averages->get('likes')->average }}" data-average-comments="{{ $averages->get('comments')->average }}" data-average-shares="{{ $averages->get('shares')->average }}">
        @endif
        <div class="video-tags container">
                <span class="badge
                        @if (!$labelFilter && !$iaFilter && !$typeFilter)
                            bg-aqua
                        @else
                            bg-green
                        @endif
                    video-label"><a href="{{ route('posts.index', ['from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}">All</a></span>
                    <span class="badge 
                        @if ($type == 'video')
                            bg-aqua
                        @else
                            bg-purple
                        @endif
                        video-label"><a href="{{ route('posts.index', ['ia' => false, 'creator' => \Request::get('creator'),
                            'label' => \Request::get('label'), 'day' => \Request::get('day'), 'type' => 'video', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}">Videos</a></span>
                    <span class="badge 
                        @if ($type == 'link')
                            bg-aqua
                        @else
                            bg-maroon
                        @endif
                        video-label"><a href="{{ route('posts.index', ['ia' => false, 'creator' => \Request::get('creator'),
                            'label' => \Request::get('label'), 'day' => \Request::get('day'), 'type' => 'link', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}">Links</a></span>
                    <span class="badge 
                        @if ($iaFilter)
                            bg-aqua
                        @else
                            bg-yellow
                        @endif
                        video-label"><a href="{{ route('posts.index', ['ia' => true, 'creator' => \Request::get('creator'),
                            'label' => \Request::get('label'), 'day' => \Request::get('day'), 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}">Instant Articles</a></span>

                    @foreach ($labels as $label)
                        <span class="badge 
                            @if ($labelFilter && $label->id == $labelFilter->id)
                                bg-aqua
                            @else
                                bg-gray
                            @endif
                        video-label"><a href="{{ route('posts.index', ['label' => $label->id, 'ia' => \Request::get('ia'),
                        'creator' => \Request::get('creator'), 'day' => \Request::get('day'), 'type' => \Request::get('type'), 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}">{{$label->label}}</a></span>
                    @endforeach
            </div>
         <hr />
         <table class="table table-striped" id="posts-table">
                <thead class="dt-center">
                    <tr>
                        <th class="dt-center">Page</th>
                        <th class="dt-center">Posted</th>
                        <th class="dt-center">Type</th>
                        <th class="dt-center">Posted by</th>
                        <th class="dt-center" data-orderable="false"></th>
                        <th class="dt-center">IA</th>
                        <th class="dt-center" data-orderable="false">Message</th>
                        <th class="dt-center" data-orderable="false">Link name</th>
                        <th class="dt-center"><i class="fa fa-eye"></i> 
                        </th>
                        <th class="dt-center"><i class="fa fa-thumbs-up"></i> 
                        </th>
                        <th class="dt-center"><i class="fa fa-comment"></i> 
                        </th>
                        <th class="dt-center"><i class="fa fa-share"></i>
                        </th>
                        <th class="dt-center"><i class="fa fa-hand-pointer-o"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr
                        @if (!is_null($post->deleted_at))
                            class="error deleted-post"
                        @endif
                        >
                            <td class="dt-center"><strong>{{ $post->page->name }}</strong></td>
                            <td class="dt-center">{{ human_since($post->posted) }}
                            <td class="dt-center">{{ title_case($post->type) }}</td>
                            @if ($post->creator)
                                <td class="dt-center"><a href="{{ route('posts.index', ['creator' => $post->creator->id, 'ia' => \Request::get('ia'), 
                                    'day' => \Request::get('day'), 'type' => \Request::get('type'), 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}">{{ $post->creator->name }}</a></td>
                            @else
                                <td class="dt-center">Unknown</td>
                            @endif
                            <td class="dt-center"><a href="/posts/{{ $post->id }}"><img src="{{ $post->picture }}" width="50"></a></td>
                            <td class="dt-center" data-sort="{{ $post->instant_article }}">
                                @if ($post->instant_article)
                                    <i class="fa fa-bolt"></i>&nbsp; 
                                @endif
                            </td>
                            <td>
                                <a href="/posts/{{ $post->id }}">
                                    {{ $post->message }}
                                </a>
                            </td>
                            <td>{{ $post->name }}</td>
                            <td class="dt-center" data-sort="{{ $post->reach }}">
                                <span class="badge
                                    @if (!$post->isUnderAverage('reach', true, $post->type))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->reach) }}</span><br />
                                    @if (!$post->isUnderAverage('reach', true, $post->type))
                                        <span class="text-success"><i class="fa fa-caret-up"></i>
                                    @else
                                        <span class="text-danger"><i class="fa fa-caret-down"></i>
                                    @endif
                                    {{ number_format($post->percentageFromTarget('reach', true, $type)) }}%</span><br />
                                <em><i class="fa fa-bullseye"></i> {{ number_format($post->getTarget('reach', true, $post->type)) }}</em>
                            </td>
                            <td class="dt-center" data-sort="{{ $post->likes }}">
                                <span class="badge
                                    @if (!$post->isUnderAverage('likes', true, $post->type))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->likes) }}</span><br />
                                    @if (!$post->isUnderAverage('likes', true, $post->type))
                                        <span class="text-success"><i class="fa fa-caret-up"></i>
                                    @else
                                        <span class="text-danger"><i class="fa fa-caret-down"></i>
                                    @endif
                                    {{ number_format($post->percentageFromTarget('likes', true, $post->type)) }}%</span><br />
                                <em><i class="fa fa-bullseye"></i> {{ number_format($post->getTarget('likes', true, $post->type)) }}</em>
                            </td>
                            <td class="dt-center" data-sort="{{ $post->comments }}">
                                <span class="badge
                                    @if (!$post->isUnderAverage('comments', true, $post->type))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->comments) }} </span><br />
                                @if (!$post->isUnderAverage('comments', true, $post->type))
                                    <span class="text-success"><i class="fa fa-caret-up"></i>
                                @else
                                    <span class="text-danger"><i class="fa fa-caret-down"></i>
                                @endif
                                {{ number_format($post->percentageFromTarget('comments', true, $post->type)) }}%</span><br />

                                <em><i class="fa fa-bullseye"></i> {{ number_format($post->getTarget('comments', true, $post->type)) }}</em>
                            </td>
                            <td class="dt-center" data-sort="{{ $post->shares }}">
                                <span class="badge
                                    @if (!$post->isUnderAverage('shares', true, $post->type))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->shares) }}</span><br />
                                @if (!$post->isUnderAverage('shares', true, $post->type))
                                    <span class="text-success"><i class="fa fa-caret-up"></i>
                                @else
                                    <span class="text-danger"><i class="fa fa-caret-down"></i>
                                @endif
                                {{ number_format($post->percentageFromTarget('shares', true, $post->type)) }}%</span><br />

                                <em><i class="fa fa-bullseye"></i> {{ number_format($post->getTarget('shares', true, $post->type)) }}</em>
                            </td>
                            <td class="dt-center"
                                @if ($post->type == 'link')
                                    data-sort="{{ $post->link_clicks }}">
                                    <span class="badge
                                    @if (!$post->isUnderAverage('link_clicks', true))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->link_clicks) }}</span><br />
                                    @if (!$post->isUnderAverage('link_clicks', true))
                                        <span class="text-success"><i class="fa fa-caret-up"></i>
                                    @else
                                        <span class="text-danger"><i class="fa fa-caret-down"></i>
                                    @endif
                                    {{ number_format($post->percentageFromTarget('link_clicks', true)) }}%</span><br />

                                    <em><i class="fa fa-bullseye"></i> {{ number_format($post->getTarget('link_clicks', true)) }}</em>
                                @else
                                    data-sort="0"></td>
                                @endif
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        @if(count($averages) > 0)
                            <th>Totals</th>
                            <th colspan="8" style="text-align: right;">
                                <span class="badge
                                    @if ($type == 'link' && ($posts->sum('reach') > $averages->get('daily_reach_article')->average))
                                        bg-green
                                    @elseif ($type == 'link')
                                        bg-red
                                    @elseif ($type == 'video' && ($posts->sum('reach') > $averages->get('daily_reach_video')->average))
                                        bg-green
                                    @elseif ($type == 'video')
                                        bg-red
                                    @elseif ($posts->sum('reach') > $averages->get('daily_reach')->average))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                ">
                                {{ number_format($posts->sum('reach')) }}<br />
                                </span>
                            </th>
                            <th>
                                <span class="badge
                                    @if ($posts->sum('likes') > $averages->get('daily_likes')->average)
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                ">
                                {{ number_format($posts->sum('likes')) }}<br />
                                </span>
                            </th>
                            <th>
                                <span class="badge
                                    @if ($type == 'link' && ($posts->sum('comments') > $averages->get('daily_comments_article')->average))
                                        bg-green
                                    @elseif ($type == 'link')
                                        bg-red
                                    @elseif ($type == 'video' && ($posts->sum('comments') > $averages->get('daily_comments_video')->average))
                                        bg-green
                                    @elseif ($type == 'video')
                                        bg-red
                                    @elseif ($posts->sum('comments') > $averages->get('daily_comments')->average))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                ">
                                {{ number_format($posts->sum('comments')) }}<br />
                                </span>
                            </th>
                            <th>
                                <span class="badge
                                    @if ($type == 'link' && ($posts->sum('shares') > $averages->get('daily_shares_article')->average))
                                        bg-green
                                    @elseif ($type == 'link')
                                        bg-red
                                    @elseif ($type == 'video' && ($posts->sum('shares') > $averages->get('daily_shares_video')->average))
                                        bg-green
                                    @elseif ($type == 'video')
                                        bg-red
                                    @elseif ($posts->sum('shares') > $averages->get('daily_shares')->average))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                ">
                                {{ number_format($posts->sum('shares')) }}<br />
                                </span>
                            </th>
                            <th>
                                <span class="badge
                                    @if ($posts->sum(function ($post) {
                                        if ($post->type == 'link') {
                                            return $post->link_clicks;
                                        }
                                        return 0;
                                        })
                                    > $averages->get('daily_link_clicks')->average))
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                ">
                                {{ number_format($posts->sum(function ($post) {
                                    if ($post->type == 'link') {
                                        return $post->link_clicks;
                                    }
                                    return 0;
                                    })
                                )}}<br />
                                </span>
                            </th>
                        @endif
                    </tr>
                </tfoot>
            </table>
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
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <script>
        $('#posts-table').dataTable({
            "paging": false
        });
        $('input[name="rangepicker"]').daterangepicker({
            maxDate: moment(),
            alwaysShowCalendars: true,
            startDate: '{{ $from->format('d/m/Y') }}',
            endDate: '{{ $to->format('d/m/Y') }}',
            locale: {
                format: 'DD/MM/YYYY'
            },
            dateLimit: {
                "days" : 14
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });
        $('input[name="rangepicker"]').on('apply.daterangepicker', function (ev, picker) {
            window.location.search += '&from=' + picker.startDate.format('YYYY-MM-DD') + '&to=' + picker.endDate.format('YYYY-MM-DD');
        });
    </script>

@endpush