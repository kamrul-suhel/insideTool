<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use App\Post;
use App\PostStatSnapshot;
use App\PostDelayedStatSnapshot;
use App\AverageMetric;

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
        $birthStats = PostStatSnapshot::where('post_id', $post->id)->where('likes', '>', 0)
            ->where('created_at', '<', \Carbon\Carbon::parse($post->posted)->addMinutes(5))
            ->orderBy('id', 'DESC')
            ->first();
        if (!$latestStats) {
            $latestStats = new PostStatSnapshot;
        }

        $postAge = (time() - strtotime($post->posted)) / 60;
        $latestStats->likespm_lifetime = ($latestStats->likes / $postAge);
        $latestStats->sharespm_lifetime = ($latestStats->shares / $postAge);
        $latestStats->commentspm_lifetime = ($latestStats->comments / $postAge);
        $latestStats->likespm_birth = ($birthStats->likes / 5);
        $latestStats->sharespm_birth = ($birthStats->shares / 5);
        $latestStats->commentspm_birth = ($birthStats->comments / 5);

        $averages = AverageMetric::all()->keyBy('key');
        return view('posts.show', ['post' => $post, 'liveLatest' => $latestStats, 
            'lastBirthStats' => $birthStats, 'averages' => $averages]);
    }

    public function jsonSnapshots(Post $post, $type, $metric, $birth = false)
    {
        if (!in_array($type, ["live", "delayed", "latest"])) {
            return response()->json(["error" => "invalid type, must be one of 'live', 'delayed', 'latest"]);
        }

        if ($metric == "all") {
            if ($type == 'live') {
                $fields = ['likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys'];

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
                $fields = ['likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys'];

                $snapshot = PostStatSnapshot::select('likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys')
                    ->where('post_id', $post->id)
                    ->where('likes', '>', 0)
                    ->orderBy('id', 'DESC')
                    ->first();
                return response()->json($snapshot);
            } else if ($type == 'delayed') {
                $fields = ['impressions', 'uniques', 'fan_impressions', 'fan_uniques'];
                $snapshots = PostDelayedStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
            }

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
