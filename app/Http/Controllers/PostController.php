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
        $posts = Post::withTrashed()->orderBy('posted', 'desc')->with('page')->get();
        return view('posts.index', ['posts' => $posts]);
    }

    public function show(Post $post)
    {
        $latestStats = PostStatSnapshot::where('post_id', $post->id)->where('likes', '>', 0)->orderBy('id', 'DESC')->first();
        return view('posts.show', ['post' => $post, 'liveLatest' => $latestStats]);
    }

    public function jsonSnapshots(Post $post, $type, $metric)
    {
        if (!in_array($type, ["live", "delayed"])) {
            return response()->json(["error" => "invalid type, must be one of 'live', 'delayed'"]);
        }

        if ($metric == "all") {
            if ($type == 'live') {
                $snapshots = PostStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
            } else {
                $snapshots = PostDelayedStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
            }

            $dropFields = ['id', 'post_id', 'facebook_id', 'updated_at'];

            // foreach ($snapshots)

        } else {
            if ($type == 'live') {
                $snapshots = PostStatSnapshot::select(['created_at as x', $metric . ' as y'])
                    ->where('post_id', $post->id)
                    ->where('likes', '>', 0)
                    ->orderBy('id', 'DESC')->get();
            } else {
                $snapshots = PostDelayedStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
            }
        }

        return response()->json($snapshots);
    }
}
