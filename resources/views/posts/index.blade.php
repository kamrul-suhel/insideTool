@extends('layouts.app')

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Posts</h3>
            <div class="box-tools pull-right video-tags">
                <span class="badge 
                        @if (!$labelFilter)
                            bg-aqua
                        @else
                            bg-gray
                        @endif
                    video-label"><a href="{{ route('posts.index') }}">All</a></span>
                @if (!$creatorFilter)
                    @foreach ($labels as $label)
                        <span class="badge 
                            @if ($labelFilter && $label->id == $labelFilter->id)
                                bg-aqua
                            @else
                                bg-gray
                            @endif
                        video-label"><a href="{{ route('posts.index', ['label' => $label->id]) }}">{{$label->label}}</a></span>
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
                            <td><a href="/posts/{{ $post->id }}">{{ $post->message }}</a></td>
                            <td>{{ $post->name }}</td>
                            <td is="index-metrics" birth="false" fields="likes,shares,comments" type="latest" post-id="{{ $post->id }}">{{ $post->name }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <div class="pull-right">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@stop

@push('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush