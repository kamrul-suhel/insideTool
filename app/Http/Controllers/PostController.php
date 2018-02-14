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
}
