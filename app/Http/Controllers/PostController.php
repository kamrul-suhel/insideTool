<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\Facebook;
use App\Creator;
use App\Post;
use App\PostStatSnapshot;
use App\VideoStatSnapshot;
use App\VideoLabel;
use App\PostDelayedStatSnapshot;
use App\AverageMetric;

class PostController extends Controller
{
    public function index()
    {
        $label = false;
        $creator = false;
        $instantArticles = false;

        if (\Request::get('creator')) {
            $creator = \Request::get('creator');
        }
        if (\Request::get('label')) {
            $label = \Request::get('label');
        }
        if (\Request::get('ia')) {
            $instantArticles = \Request::get('ia');
        }
        
        $posts = Post::withTrashed()
            ->orderBy('posted', 'desc')
            ->with(['page', 'creator']);
        $labelFilter = false;
        $creatorFilter = false;
        $iaFilter = false;

        if ($label) {
            $posts = $posts->whereHas('videoLabels', function ($q) use ($label) {
                $q->where('id', (int) $label);
            });
            $labelFilter = VideoLabel::find($label);
        }
        if ($creator) {
            $posts = $posts->whereHas('creator', function ($q) use ($creator) {
                $q->where('id', (int) $creator);
            });
            $creatorFilter = Creator::find($creator);
        }
        if ($instantArticles) {
            $posts->where('instant_article', true);
            $iaFilter = true;
        }
        $labels = VideoLabel::all();
        $posts = $posts->paginate(20);
        $averages = AverageMetric::all()->keyBy('key');
        return view('posts.index', ['posts' => $posts, 'averages' => $averages, 'labelFilter' => $labelFilter, 
            'labels' => $labels, 'creatorFilter' => $creatorFilter, 'iaFilter' => $iaFilter]);
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
        $latestStats->likespm_lifetime = $latestStats ? ($latestStats->likes / $postAge) : 0;
        $latestStats->sharespm_lifetime = $latestStats ? ($latestStats->shares / $postAge) : 0;
        $latestStats->commentspm_lifetime = $latestStats ? ($latestStats->comments / $postAge) : 0;
        $latestStats->likespm_birth = $birthStats ? ($birthStats->likes / 5) : 0;
        $latestStats->sharespm_birth = $birthStats ? ($birthStats->shares / 5) : 0;
        $latestStats->commentspm_birth = $birthStats ? ($birthStats->comments / 5) : 0;

        $averages = AverageMetric::all()->keyBy('key');
        return view('posts.show', ['post' => $post, 'liveLatest' => $latestStats, 
            'lastBirthStats' => $birthStats, 'averages' => $averages]);
    }

    public function jsonSnapshots(Request $request, Post $post, $type, $birth = false)
    {
        if (!in_array($type, ["live", "delayed", "latest", "video"])) {
            return response()->json(["error" => "invalid type, must be one of 'live', 'delayed', 'latest', 'video'"]);
        }

        $fields = explode(',',$request->input('fields'));

        //if ($metric == "all") {
            if ($type == 'live') {
                    //$fields = ['likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys'];

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
                //$fields = ['likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys'];

                $snapshot = PostStatSnapshot::select('likes', 'shares', 'comments', 'loves', 'hahas', 'wows', 'sads', 'angrys')
                    ->where('post_id', $post->id)
                    ->where('likes', '>', 0)
                    ->orderBy('id', 'DESC')
                    ->first();
                return response()->json($snapshot);
            } else if ($type == 'delayed') {
                //$fields = ['impressions', 'uniques', 'fan_impressions', 'fan_uniques'];
                $snapshots = PostDelayedStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
            } else if ($type == 'video') {
                $snapshots = VideoStatSnapshot::where('post_id', $post->id)->orderBy('id', 'DESC')->get();
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
                            "x" => (string) $snapshot->created_at,
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

    public function getMetricColor($metric, $opacity = 1){
        switch($metric){
            case 'likes':
                $color = 'rgba(0, 192 ,239, '.$opacity.')';
                break;
            case 'shares':
                $color = 'rgba(0, 166, 9, '.$opacity.')';
                break;
            case 'comments':
                $color = 'rgba(96, 92 ,168, '.$opacity.')';
                break;
            case 'loves':
                $color = 'rgba(216, 27, 96, '.$opacity.')';
                break;
            case 'wows':
                $color = 'rgba(0, 166, 90, '.$opacity.')';
                break;
            case 'hahas':
                $color = 'rgba(57, 204, 204, '.$opacity.')';
                break;
            case 'sads':
                $color = 'rgba(0, 185, 183, '.$opacity.')';
                break;
            case 'angrys':
                $color = 'rgba(221, 75, 157, '.$opacity.')';
                break;
            case 'impressions':
                $color = 'rgba(216, 27, 96, '.$opacity.')';
                break;
            case 'total_video_views':
                $color = 'rgba(0, 192 ,239, '.$opacity.')';
                break;
            case 'total_video_views_autoplayed':
                $color = 'rgba(0, 166, 9, '.$opacity.')';
                break;
            case 'total_video_views_clicked_to_play':
                $color = 'rgba(96, 92 ,168, '.$opacity.')';
                break;
            case 'total_video_complete_views':
                $color = 'rgba(216, 27, 96, '.$opacity.')';
                break;
            case 'uniques':
            default:
                $color = 'rgba(0, 166 ,90, '.$opacity.')';
                break;
        }

        return $color;
    }

    protected function getMetricLabel($metric) {
        switch($metric) {
            case 'total_video_views':
                $label = 'Total Views';
                break;
            case 'total_video_views_autoplayed':
                $label = 'Total Views (Autoplayed)';
                break;
            case 'total_video_views_clicked_to_play';
                $label = 'Total Views (Clicked to play)';
                break;
            case 'total_video_complete_views':
                $label = 'Total Complete Views';
                break;
            default:
                $label = title_case(str_replace('_', ' ', $metric));
                break;
        }
        return $label;
    }
}
