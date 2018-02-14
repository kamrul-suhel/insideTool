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
                        <th>Type</th>
                        <th></th>
                        <th>Message</th>
                        <th>Link name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td><strong>{{ $post->page->name }}</strong></td>
                            <td>{{ title_case($post->type) }}</td>
                            <td><a href="/posts/{{ $post->id }}"><img src="{{ $post->picture }}" width="50"></a></td>
                            <td><a href="/posts/{{ $post->id }}">{{ $post->message }}</a></td>
                            <td>{{ $post->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
