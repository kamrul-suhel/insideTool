@extends('layouts.app')

@section('content_header')
    <h1>{{ $date->format('l, jS F Y') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Videos</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-eye"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Reach</span>
                                    <span class="info-box-number info-box-number-medium">
                                        {{ number_format($videoReach) }}
                                    </span>                 
                                    @if ($videoReach < $averages->get('daily_reach_video')->average)
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ round(($videoReach / $averages->get('daily_reach_video')->average) * 100) }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($averages->get('daily_reach_video')->average - $videoReach) / $averages->get('daily_reach_video')->average * 100) }}% below average
                                        </span>

                                    @else
                                        <div class="progress">
                                            <div class="progress-bar" style="width:100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($videoReach - $averages->get('daily_reach_video')->average) / $averages->get('daily_reach_video')->average * 100) }}% above average
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-teal">
                                <span class="info-box-icon"><i class="fa fa-thumbs-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Reactions</span>
                                    <span class="info-box-number info-box-number-medium">
                                        {{ number_format($videoReactions) }}
                                    </span>
                                    @if ($videoReactions < $averages->get('daily_reactions_video')->average)
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ round(($videoReactions / $averages->get('daily_reactions_video')->average) * 100) }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($averages->get('daily_reactions_video')->average - $videoReactions) / $averages->get('daily_reactions_video')->average * 100) }}% below average
                                        </span>
                                    @else
                                        <div class="progress">
                                            <div class="progress-bar" style="width:100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($videoReactions - $averages->get('daily_reactions_video')->average) / $averages->get('daily_reactions_video')->average * 100) }}% above average
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-share"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Shares</span>
                                    <span class="info-box-number info-box-number-medium">
                                        {{ number_format($videoShares) }}
                                    </span>
                                    @if ($videoShares < $averages->get('daily_shares_video')->average)
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ round(($videoShares / $averages->get('daily_shares_video')->average) * 100) }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($averages->get('daily_shares_video')->average - $videoShares) / $averages->get('daily_shares_video')->average * 100) }}% below average
                                        </span>

                                    @else
                                        <div class="progress">
                                            <div class="progress-bar" style="width:100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($videoShares - $averages->get('daily_shares_video')->average) / $averages->get('daily_shares_video')->average * 100) }}% above average
                                        </span>
                                    @endif   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-comment"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Comments</span>
                                    <span class="info-box-number info-box-number-medium">
                                        {{ number_format($videoComments) }}
                                    </span>
                                    @if ($videoComments < $averages->get('daily_comments_video')->average)
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ round(($videoComments / $averages->get('daily_comments_video')->average) * 100) }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($averages->get('daily_comments_video')->average - $videoComments) / $averages->get('daily_comments_video')->average * 100) }}% below average
                                        </span>

                                    @else
                                        <div class="progress">
                                            <div class="progress-bar" style="width:100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($videoComments - $averages->get('daily_comments_video')->average) / $averages->get('daily_comments_video')->average * 100) }}% above average
                                        </span>
                                    @endif               
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Articles</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-eye"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Reach</span>
                                    <span class="info-box-number info-box-number-medium">
                                        {{ number_format($articleReach) }}
                                    </span>                 
                                    @if ($articleReach < $averages->get('daily_reach_article')->average)
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ round(($articleReach / $averages->get('daily_reach_article')->average) * 100) }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($averages->get('daily_reach_article')->average - $articleReach) / $averages->get('daily_reach_article')->average * 100) }}% below average
                                        </span>

                                    @else
                                        <div class="progress">
                                            <div class="progress-bar" style="width:100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($articleReach - $averages->get('daily_reach_article')->average) / $averages->get('daily_reach_article')->average * 100) }}% above average
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-teal">
                                <span class="info-box-icon"><i class="fa fa-thumbs-up"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Reactions</span>
                                    <span class="info-box-number info-box-number-medium">
                                        {{ number_format($articleReactions) }}
                                    </span>
                                    @if ($articleReactions < $averages->get('daily_reactions_article')->average)
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ round(($articleReactions / $averages->get('daily_reactions_article')->average) * 100) }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($averages->get('daily_reactions_article')->average - $articleReactions) / $averages->get('daily_reactions_article')->average * 100) }}% below average
                                        </span>
                                    @else
                                        <div class="progress">
                                            <div class="progress-bar" style="width:100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($articleReactions - $averages->get('daily_reactions_article')->average) / $averages->get('daily_reactions_article')->average * 100) }}% above average
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-share"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Shares</span>
                                    <span class="info-box-number info-box-number-medium">
                                        {{ number_format($articleShares) }}
                                    </span>
                                    @if ($articleShares < $averages->get('daily_shares_article')->average)
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ round(($articleShares / $averages->get('daily_shares_article')->average) * 100) }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($averages->get('daily_shares_article')->average - $articleShares) / $averages->get('daily_shares_article')->average * 100) }}% below average
                                        </span>

                                    @else
                                        <div class="progress">
                                            <div class="progress-bar" style="width:100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($articleShares - $averages->get('daily_shares_article')->average) / $averages->get('daily_shares_article')->average * 100) }}% above average
                                        </span>
                                    @endif   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-comment"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Comments</span>
                                    <span class="info-box-number info-box-number-medium">
                                        {{ number_format($articleComments) }}
                                    </span>
                                    @if ($articleComments < $averages->get('daily_comments_article')->average)
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ round(($articleComments / $averages->get('daily_comments_article')->average) * 100) }}%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($averages->get('daily_comments_article')->average - $articleComments) / $averages->get('daily_comments_article')->average * 100) }}% below average
                                        </span>

                                    @else
                                        <div class="progress">
                                            <div class="progress-bar" style="width:100%"></div>
                                        </div>
                                        <span class="progress-description">
                                            {{ round(($articleComments - $averages->get('daily_comments_article')->average) / $averages->get('daily_comments_article')->average * 100) }}% above average
                                        </span>
                                    @endif               
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Posts</h3>
            <div class="box-tools pull-right video-tags">
                <span class="badge 
                        @if (!$labelFilter && !$iaFilter)
                            bg-aqua
                        @else
                            bg-gray
                        @endif
                    video-label"><a href="{{ route('posts.index') }}">All</a></span>
                    <span class="badge 
                        @if ($iaFilter)
                            bg-aqua
                        @else
                            bg-yellow
                        @endif
                        video-label"><a href="{{ route('posts.index', ['ia' => true, 'creator' => \Request::get('creator'),
                            'label' => \Request::get('label'), 'day' => \Request::get('day')]) }}">Instant Articles</a></span>

                @if (!$creatorFilter)
                    @foreach ($labels as $label)
                        <span class="badge 
                            @if ($labelFilter && $label->id == $labelFilter->id)
                                bg-aqua
                            @else
                                bg-gray
                            @endif
                        video-label"><a href="{{ route('posts.index', ['label' => $label->id, 'ia' => \Request::get('ia'),
                        'creator' => \Request::get('creator'), 'day' => \Request::get('day')]) }}">{{$label->label}}</a></span>
                    @endforeach
                @endif
            </div>
        </div>
        @if ($creatorFilter)
            <div class="box-header">
                <div class="box-tools"><p>Showing posts by: <span class="badge bg-red creator"><a href="{{ route('posts.index') }}">{{ $creatorFilter->name }} <i class="fa fa-times"></i></a></p></div>
            </div>
        @endif
        <div id="app" class="box-body table-responsive no-padding" data-average-likes="{{ $averages->get('likes')->average }}"
         data-average-comments="{{ $averages->get('comments')->average }}"  data-average-shares="{{ $averages->get('shares')->average }}">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Posted</th>
                        <th>Type</th>
                        <th>Posted by</th>
                        <th></th>
                        <th>Message</th>
                        <th>Link name</th>
                        <th class="col-sm-2">Stats</th>
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
                                <td><a href="{{ route('posts.index', ['creator' => $post->creator->id]) }}">{{ $post->creator->name }}</a></td>
                            @else
                                <td>Unknown</td>
                            @endif
                            <td><a href="/posts/{{ $post->id }}"><img src="{{ $post->picture }}" width="50"></a></td>
                            <td>
                                @if ($post->instant_article)
                                    <i class="fa fa-bolt"></i>&nbsp; 
                                @endif
                                <a href="/posts/{{ $post->id }}">
                                    {{ $post->message }}
                                </a>
                            </td>
                            <td>{{ $post->name }}</td>
                            <td is="index-metrics" birth="false" fields="likes,shares,comments" type="latest" post-id="{{ $post->id }}">{{ $post->name }}</td>

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

                {{-- {{ $posts->links() }} --}}
            </div>
        </div>
    </div>
@stop

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush