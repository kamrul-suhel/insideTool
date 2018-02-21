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
        if (!$latestStats) {
            $latestStats = new PostStatSnapshot;
        }
        return view('posts.show', ['post' => $post, 'liveLatest' => $latestStats]);
    }

    public function jsonSnapshots(Post $post, $type, $metric, $birth = false)
    {
        if (!in_array($type, ["live", "delayed", "latest"])) {
            return response()->json(["error" => "invalid type, must be one of 'live', 'delayed', 'latest"]);
        }

        if ($metric == "all") {
            if ($type == 'live') {
                if ($birth) {
                    $birthEndDate = new \Carbon\Carbon($post->posted);
                    $birthEndDate = $birthEndDate->addMinutes(5)->format('Y-m-d H:i:s');
                    $snapshots = PostStatSnapshot::where('post_id', $post->id)
                        ->whereRaw('created_at BETWEEN \'' . $post->posted . '\' AND \'' . $birthEndDate . '\'')
                        ->where('likes', '>', 0)
                        ->orderBy('id', 'DESC')
                        ->get();
                } else {
                    $snapshots = PostStatSnapshot::where('post_id', $post->id)
                    ->where('likes', '>', 0)
                    ->orderBy('id', 'DESC')->get();
                }
            } else if ($type == 'latest') {
                $snapshot = PostStatSnapshot::select('likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys')
                    ->where('post_id', $post->id)
                    ->where('likes', '>', 0)
                    ->orderBy('id', 'DESC')
                    ->first();
                return response()->json($snapshot);
            } else {
                $snapshots = PostDelayedStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
            }

            $fields = ['likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys'];

            $response = [];

            if ($snapshots) {
                foreach ($fields as $field) {
                    foreach ($snapshots as $snapshot) {
                        $response[$field][] = [
                            "x" => (string) $snapshot->created_at,
                            "y" => $snapshot->$field
                        ];
                    }
                }
            }
            
            return response()->json($response);
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
