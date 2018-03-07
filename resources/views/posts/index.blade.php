@extends('layouts.app')

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Posts</h3>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Posted</th>
                        <th>Type</th>
                        <th></th>
                        <th>Message</th>
                        <th>Link name</th>
                        <th><i class="fa fa-thumbs-up"></th>
                        <th><i class="fa fa-comment"></th>
                        <th><i class="fa fa-share"></th>
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
                            <td><a href="/posts/{{ $post->id }}"><img src="{{ $post->picture }}" width="50"></a></td>
                            <td><a href="/posts/{{ $post->id }}">{{ $post->message }}</a></td>
                            <td>{{ $post->name }}</td>
                            <td>
                                <span class="badge
                                @if ($post->latestStatSnapshot()->likes > $averages->get('likes')->average)
                                    bg-green
                                @else
                                    bg-red
                                @endif
                                ">
                                    {{ $post->latestStatSnapshot()->likes }}
                                </span>
                            </td>
                            <td>
                                <span class="badge
                                @if ($post->latestStatSnapshot()->comments > $averages->get('comments')->average)
                                    bg-green
                                @else
                                    bg-red
                                @endif
                                ">
                                    {{ $post->latestStatSnapshot()->comments }}
                                </span>
                            </td>
                            <td>
                                <span class="badge
                                @if ($post->latestStatSnapshot()->shares > $averages->get('shares')->average)
                                    bg-green
                                @else
                                    bg-red
                                @endif
                                ">
                                    {{ $post->latestStatSnapshot()->shares }}
                                </span>
                            </td>
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
