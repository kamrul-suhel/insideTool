@extends('layouts.app')

@section('content_header')
    <h1>{{ $date->format('l, jS F Y') }}</h1>
@stop

@section('content')
<div id="app">
    <?php $day_percentage = $date->isToday() ? (date('H') / 24 + date('i') / (60 * 24)) : 1;?>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Videos</h3>
                </div>

                <div class="box-body">
                    <graph-metric id="video-reach-metric" color="aqua" fa-icon="eye" actual="<?php echo $videoReach; ?>" target="<?php echo $averages->get('daily_reach_video')->average; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                    <graph-metric id="video-comment-metric" color="yellow" fa-icon="comment" actual="<?php echo $videoComments; ?>" target="<?php echo $averages->get('daily_comments_video')->average; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                    <graph-metric id="video-shares-metric" color="green" fa-icon="share" actual="<?php echo $videoShares; ?>" target="<?php echo $averages->get('daily_shares_video')->average; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                    <graph-metric id="video-reactions-metric" color="teal" fa-icon="thumbs-up" actual="<?php echo $videoReactions; ?>" target="<?php echo $averages->get('daily_reactions_video')->average; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Articles</h3>
                </div>

                <div class="box-body">
                    <graph-metric id="article-reach-metric" color="aqua" fa-icon="eye" actual="<?php echo $articleReach; ?>" target="<?php echo $averages->get('daily_reach_article')->average; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                    <graph-metric id="article-comment-metric" color="yellow" fa-icon="comment" actual="<?php echo $articleComments; ?>" target="<?php echo $averages->get('daily_comments_article')->average; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                    <graph-metric id="article-shares-metric" color="green" fa-icon="share" actual="<?php echo $articleShares; ?>" target="<?php echo $averages->get('daily_shares_article')->average; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                    <graph-metric id="article-reactions-metric" color="teal" fa-icon="thumbs-up" actual="<?php echo $articleReactions; ?>" target="<?php echo $averages->get('daily_reactions_article')->average; ?>" day-percentage="<?php echo $day_percentage; ?>"></graph-metric>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon"><i class="fa fa-eye"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Reach</span>
                    <span class="info-box-number info-box-number-big">
                        {{ number_format($articleReach + $videoReach) }}
                    </span>             
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon"><i class="fa fa-thumb-tack"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Posts</span>
                    <span class="info-box-number info-box-number-big">
                        {{ count($posts) }}
                    </span>             
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-box">
                <span class="info-box-icon"><i class="fa fa-play"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Videos</span>
                    <span class="info-box-number info-box-number-big">
                        {{ count($posts->where('type', 'video')) }}
                    </span>             
                </div>
            </div>
        </div>

        </div>
        <div class="row">

        <div class="col-md-4">
            <div class="info-box">
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
            <div class="info-box">
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
        <div class="box-header">
            <h3 class="box-title">Posts</h3>
        </div>
        @if ($creatorFilter)
            <div class="box-header">
                <div class="box-tools"><p>Showing posts by: <span class="badge bg-red creator"><a href="{{ route('posts.index') }}">{{ $creatorFilter->name }} <i class="fa fa-times"></i></a></p></div>
            </div>
        @endif
        <div class="box-body averages" data-average-likes="{{ $averages->get('likes')->average }}"
         data-average-comments="{{ $averages->get('comments')->average }}"  data-average-shares="{{ $averages->get('shares')->average }}">
         <div class="pull-right video-tags">
                <span class="badge 
                        @if (!$labelFilter && !$iaFilter && !$typeFilter)
                            bg-aqua
                        @else
                            bg-green
                        @endif
                    video-label"><a href="{{ route('posts.index') }}">All</a></span>
                    <span class="badge 
                        @if ($type == 'video')
                            bg-aqua
                        @else
                            bg-purple
                        @endif
                        video-label"><a href="{{ route('posts.index', ['ia' => false, 'creator' => \Request::get('creator'),
                            'label' => \Request::get('label'), 'day' => \Request::get('day'), 'type' => 'video']) }}">Videos</a></span>
                    <span class="badge 
                        @if ($type == 'link')
                            bg-aqua
                        @else
                            bg-maroon
                        @endif
                        video-label"><a href="{{ route('posts.index', ['ia' => false, 'creator' => \Request::get('creator'),
                            'label' => \Request::get('label'), 'day' => \Request::get('day'), 'type' => 'link']) }}">Links</a></span>
                    <span class="badge 
                        @if ($iaFilter)
                            bg-aqua
                        @else
                            bg-yellow
                        @endif
                        video-label"><a href="{{ route('posts.index', ['ia' => true, 'creator' => \Request::get('creator'),
                            'label' => \Request::get('label'), 'day' => \Request::get('day')]) }}">Instant Articles</a></span>

                    @foreach ($labels as $label)
                        <span class="badge 
                            @if ($labelFilter && $label->id == $labelFilter->id)
                                bg-aqua
                            @else
                                bg-gray
                            @endif
                        video-label"><a href="{{ route('posts.index', ['label' => $label->id, 'ia' => \Request::get('ia'),
                        'creator' => \Request::get('creator'), 'day' => \Request::get('day'), 'type' => \Request::get('type')]) }}">{{$label->label}}</a></span>
                    @endforeach
            </div>
         <br />
         <table class="table table-striped" id="posts-table">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Posted</th>
                        <th>Type</th>
                        <th>Posted by</th>
                        <th data-orderable="false"></th>
                        <th>IA</th>
                        <th data-orderable="false">Message</th>
                        <th data-orderable="false">Link name</th>
                        <th><i class="fa fa-eye"></i></th>
                        <th><i class="fa fa-thumbs-up"></i></th>
                        <th><i class="fa fa-comment"></i></th>
                        <th><i class="fa fa-share"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr
                        @if (!is_null($post->deleted_at))
                            class="error deleted-post"
                        @endif
                        >
                            <td><strong>{{ $post->page->name }}</strong></td>
                            <td>{{ date("d/m/Y H:i:s", strtotime($post->posted)) }}
                            <td>{{ title_case($post->type) }}</td>
                            @if ($post->creator)
                                <td><a href="{{ route('posts.index', ['creator' => $post->creator->id, 'ia' => \Request::get('ia'), 
                                    'day' => \Request::get('day'), 'type' => \Request::get('type')]) }}">{{ $post->creator->name }}</a></td>
                            @else
                                <td>Unknown</td>
                            @endif
                            <td><a href="/posts/{{ $post->id }}"><img src="{{ $post->picture }}" width="50"></a></td>
                            <td data-sort="{{ $post->instant_article }}">
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
                            <td data-sort="{{ $post->reach }}">
                                <span class="badge
                                    @if ($post->reach > $averages->get('reach')->average)
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->reach) }} / <span class="text-light">{{ round(($post->reach / $averages->get('reach')->average) * 100) }}%</span>
                                </span>
                            </td>
                            <td data-sort="{{ $post->likes }}">
                                <span class="badge
                                    @if ($post->likes > $averages->get('likes')->average)
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->likes) }} / <span class="text-light">{{ round(($post->likes / $averages->get('likes')->average) * 100) }}%</span>
                                </span>
                            </td>
                            <td data-sort="{{ $post->comments }}">
                                <span class="badge
                                    @if ($post->comments > $averages->get('comments')->average)
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->comments) }} / <span class="text-light">{{ round(($post->comments / $averages->get('comments')->average) * 100) }}%</span>
                                </span>
                            </td>
                            <td data-sort="{{ $post->shares }}">
                                <span class="badge
                                    @if ($post->shares > $averages->get('shares')->average)
                                        bg-green
                                    @else
                                        bg-red
                                    @endif
                                     ">
                                    {{ number_format($post->shares) }} / <span class="text-light">{{ round(($post->shares / $averages->get('shares')->average) * 100) }}%</span>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <div class="pull-right">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item
                            @if (!$paginationLinks["prevLink"])
                                disabled
                            @endif
                        ">
                            <a class="page-link" href="{{ $paginationLinks["prevLink"] }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        @foreach ($paginationLinks["days"] as $link)
                            <li class="page-item
                                @if ($link['current'])
                                    active
                                @endif
                            "><a class="page-link" href="{{ $link['link'] }}">{{ $link["label"] }}</a></li>
                        @endforeach
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginationLinks["nextLink"] }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/dataTables.bootstrap.min.js"></script>
    <script>
        $('#posts-table').dataTable({
            "paging": false
        });
    </script>

@endpush