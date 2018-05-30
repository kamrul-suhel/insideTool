<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Post;
use App\PostStatSnapshot;
use App\VideoStatSnapshot;
use App\PostDelayedStatSnapshot;
use App\AverageMetric;
use Illuminate\View\View;

class PostController extends Controller
{
    protected
        $post,
        $videoStatSnapshot,
        $averageMetric,
        $postStatSnapshot,
        $postDelayedStatSnapshot;

    public function __construct(
        Post $post, AverageMetric
        $averageMetric, PostStatSnapshot
        $postStatSnapshot, PostDelayedStatSnapshot
        $postDelayedStatSnapshot,
        VideoStatSnapshot $videoStatSnapshot)
    {
        $this->post = $post;

        $this->averageMetric = $averageMetric;

        $this->postStatSnapshot = $postStatSnapshot;

        $this->postDelayedStatSnapshot = $postDelayedStatSnapshot;

        $this->videoStatSnapshot = $videoStatSnapshot;
    }

    /**
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Post $post) : View
    {
        $latestStats = $post->latestStatSnapshot();
        $latestDelayedStats = $post->latestDelayedStatSnapshot();
        $birthStats = $post->birthStatSnapshot();

        $postAge = (time() - strtotime($post->posted)) / 60;
        $latestStats->likespm_lifetime = $latestStats ? ($latestStats->likes / $postAge) : 0;
        $latestStats->sharespm_lifetime = $latestStats ? ($latestStats->shares / $postAge) : 0;
        $latestStats->commentspm_lifetime = $latestStats ? ($latestStats->comments / $postAge) : 0;
        $latestStats->likespm_birth = $birthStats ? ($birthStats->likes / 5) : 0;
        $latestStats->sharespm_birth = $birthStats ? ($birthStats->shares / 5) : 0;
        $latestStats->commentspm_birth = $birthStats ? ($birthStats->comments / 5) : 0;
        $latestStats->reactionspm_lifetime = $latestStats ? (($latestStats->reactions + $latestStats->likes + $latestStats->shares + $latestStats->comments) / $postAge) : 0;
        $latestStats->reactionspm_birth = $birthStats ? (($birthStats->reactions + $birthStats->likes + $birthStats->shares + $birthStats->comments) / 5) : 0;

        $latestDelayedStats->reachpm_lifetime = $latestDelayedStats ? ($latestDelayedStats->impressions / $postAge) : 0;

        $averages = $this->averageMetric->all()->keyBy('key');

        return view('posts.show', [
            'post' => $post,
            'liveLatest' => $latestStats,
            'delayedLatest' => $latestDelayedStats,
            'lastBirthStats' => $birthStats,
            'averages' => $averages]
        );
    }

    /**
     * API call for snapshot data for posts
     * @param Request $request
     * @param Post $post
     * @param $type
     * @param bool $birth
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonSnapshots(Request $request, Post $post, $type, $birth = false) : JsonResponse
    {
        if (!in_array($type, ["live", "delayed", "latest", "video"])) {
            return response()->json(["error" => "invalid type, must be one of 'live', 'delayed', 'latest', 'video'"]);
        }

        $fields = explode(',', $request->input('fields'));

        //if ($metric == "all") {
        if ($type == 'live') {
            //$fields = ['likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys'];

            if ($birth) {
                $birthEndDate = new \Carbon\Carbon($post->posted);
                $birthEndDate = $birthEndDate->addMinutes(5)->format('Y-m-d H:i:s');
                $snapshots = $this->postStatSnapshot->where('post_id', $post->id)
                    ->whereRaw('created_at BETWEEN \'' . $post->posted . '\' AND \'' . $birthEndDate . '\'')
                    ->where('likes', '>', 0)
                    ->orderBy('id', 'DESC')
                    ->get();
            } else {
                $snapshots = $this->postStatSnapshot->where('post_id', $post->id)
                    ->where('likes', '>', 0)
                    ->orderBy('id', 'DESC')->get();
            }
        } else if ($type == 'latest') {
            //$fields = ['likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys'];

            $snapshot = $this->postStatSnapshot->select('likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys')
                ->where('post_id', $post->id)
                ->where('likes', '>', 0)
                ->orderBy('id', 'DESC')
                ->first();
            return response()->json($snapshot);
        } else if ($type == 'delayed') {
            //$fields = ['impressions', 'uniques', 'fan_impressions', 'fan_uniques'];
            $snapshots = $this->postDelayedStatSnapshot->where('post_id', $post->id)->orderBy('id', 'DESC')->get();
        } else if ($type == 'video') {
            $snapshots = $this->videoStatSnapshot->where('post_id', $post->id)->orderBy('id', 'DESC')->get();
        }

        $response = [];

        if ($snapshots) {
            foreach ($fields as $key => $field) {
                $response[$key]['label'] = $this->getMetricLabel($field);
                $response[$key]['backgroundColor'] = $this->getMetricColor($field, 0.5);
                $response[$key]['borderColor'] = $this->getMetricColor($field);
                $response[$key]['borderWidth'] = 0;
                $response[$key]['fill'] = false;

                foreach ($snapshots as $snapshot) {
                    $response[$key]['data'][] = [
                        "x" => (string)$snapshot->created_at,
                        "y" => $snapshot->$field
                    ];
                }
            }
        }

        return response()->json($response);
        // } else {
        //     if ($type == 'live') {
        //         $snapshots = PostStatSnapshot::select(['created_at as x', $metric . ' as y'])
        //             ->where('post_id', $post->id)
        //             ->where('likes', '>', 0)
        //             ->orderBy('id', 'DESC')->get();
        //     } else {
        //         $snapshots = PostDelayedStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
        //     }
        // }

        //return response()->json($snapshots);
    }

    /**
     * Set metric colour
     * @param $metric
     * @param int $opacity
     * @return string
     */
    public function getMetricColor($metric, $opacity = 1) : string
    {
        $metric = $metric == "" ? $metric = 'colors.uniques.rgb': 'colors.'.$metric.'.rgb';
        return __($metric, ['opacity' => $opacity]);
    }

    /**
     * Set metric label
     * @param $metric
     * @return string
     */
    protected function getMetricLabel($metric) : string
    {
        $metric = $metric == "" ? $metric = title_case(str_replace('_', ' ', $metric)): 'colors.'.$metric.'.label';
        return __($metric);
    }
}
