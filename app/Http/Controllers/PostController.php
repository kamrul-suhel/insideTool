<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use App\Post;
use App\PostStatSnapshot;
use App\PostDelayedStatSnapshot;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('posted', 'desc')->with('page')->get();
        return view('posts.index', ['posts' => $posts]);
    }

    public function show(Post $post)
    {
        $latestStats = PostStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->first();
        return view('posts.show', ['post' => $post, 'liveLatest' => $latestStats]);
    }

    public function jsonSnapshots(Post $post, $type, $metric)
    {
        if ($type == 'live') {
            $snapshots = PostStatSnapshot::select(['created_at as x', $metric . ' as y'])->where('post_id', $post->id)->orderBy('id', 'DESC')->get();
        } else if ($type == 'delayed') {
            $snapshots = PostDelayedStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
        } else {
            $snapshots = ["error" => "invalid snapshot type"];
        }

        $response = [
            'labels' => [$snapshots->last()->x, 
                $snapshots->get((int) floor(count($snapshots) / 2))->x,
                $snapshots->first()->x],
            'data' => $snapshots
        ];

        return response()->json($response);
    }
}
