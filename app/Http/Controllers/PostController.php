<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Creator;
use App\Post;
use App\PostStatSnapshot;
use App\VideoStatSnapshot;
use App\VideoLabel;
use App\PostDelayedStatSnapshot;
use App\AverageMetric;
use Yajra\Datatables\Datatables;

class PostController extends Controller
{

    protected $post, $articleImpressions, $articleReactions, $articleShares,
        $articleComments, $videoImpressions, $videoReactions, $videoShares, $videoComments,
        $label, $creator, $instantArticles, $type, $labelFilter, $creatorFilter, $iaFilter,
        $typeFilter, $from, $to;

    /**
     * PostController constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->creator = \Request::get('creator');
        $this->label = \Request::get('label');
        $this->instantArticles = \Request::get('ia');
        $this->type = \Request::get('type');
        $this->from = \Request::get('from');
        $this->to = \Request::get('to');

        $posts = $this->post->getAllPosts();

        if ($this->label) {
            $posts = $this->post->whereHas('videoLabels', $this->label);
            $this->labelFilter = VideoLabel::find($this->label);
        }
        if ($this->creator) {
            $posts = $this->post->whereHas('creator', $this->creator);
            $this->creatorFilter = Creator::find($this->creator);
        }
        if ($this->instantArticles) {
            $posts->where('instant_article', true);
            $this->iaFilter = true;
        }
        if ($this->type) {
            $posts->where('type', $this->type);
            $this->typeFilter = true;
        }

        $this->from = $this->from ? \Carbon\Carbon::parse(\Request::get('from')) : \Carbon\Carbon::now()->startOfDay();
        $this->to = $this->to ? \Carbon\Carbon::parse(\Request::get('to'))->endOfDay() :  \Carbon\Carbon::now()->endOfDay();

        $posts->whereBetween('posted', [$this->from->toDateTimeString(), $this->to->toDateTimeString()]);
        $daysInRange = $this->from->diffInDays($this->to) + 1;

        $labels = VideoLabel::all();

        $posts = $posts->get();

        $this->processPosts($posts);

        $averages = AverageMetric::all()->keyBy('key');

        return view('posts.index',
            [
                'posts' => $posts,
                'averages' => $averages,
                'labelFilter' => $this->labelFilter,
                'labels' => $labels,
                'creatorFilter' => $this->creatorFilter,
                'iaFilter' => $this->iaFilter,
                'typeFilter' => $this->typeFilter,
                'videoReach' => $this->videoImpressions,
                'videoReactions' => $this->videoReactions,
                'videoShares' => $this->videoShares,
                'videoComments' => $this->videoComments,
                'articleReach' => $this->articleImpressions,
                'articleReactions' => $this->articleReactions,
                'articleShares' => $this->articleShares,
                'articleComments' => $this->articleComments,
                'type' => $this->type,
                'daysInRange' => $daysInRange,
                'from' => $this->from,
                'to' => $this->to
            ]);
    }

    /**
     * @param $posts
     */
    public function processPosts($posts)
    {
        foreach ($posts as $post) {
            if ($post->type == 'video') {
                $this->videoImpressions += $post->reach;
            } else if ($post->type == 'link') {
                $this->articleImpressions += $post->reach;
            }

            if ($post->type == 'video') {
                $this->videoReactions += $post->reactions;
                $this->videoShares += $post->shares;
                $this->videoComments += $post->comments;
            } else if ($post->type == 'link') {
                $this->articleReactions += $post->reactions;
                $this->articleShares += $post->shares;
                $this->articleComments += $post->comments;
            }
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function indexDatatables()
    {
        $posts = Post::withTrashed()
            ->orderBy('posted', 'desc')
            ->with(['page', 'creator']);
        return Datatables::of($posts)
            ->make(true);
    }

    /**
     * @param Post $post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Post $post)
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

        $averages = AverageMetric::all()->keyBy('key');
        return view('posts.show', ['post' => $post, 'liveLatest' => $latestStats, 'delayedLatest' => $latestDelayedStats,
            'lastBirthStats' => $birthStats, 'averages' => $averages]);
    }

    /**
     * @param Request $request
     * @param Post $post
     * @param $type
     * @param bool $birth
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonSnapshots(Request $request, Post $post, $type, $birth = false)
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
     * @param $metric
     * @param int $opacity
     * @return string
     */
    public function getMetricColor($metric, $opacity = 1)
    {
        switch ($metric) {
            case 'likes':
                $color = 'rgba(0, 192 ,239, ' . $opacity . ')';
                break;
            case 'shares':
                $color = 'rgba(0, 166, 9, ' . $opacity . ')';
                break;
            case 'comments':
                $color = 'rgba(96, 92 ,168, ' . $opacity . ')';
                break;
            case 'loves':
                $color = 'rgba(216, 27, 96, ' . $opacity . ')';
                break;
            case 'wows':
                $color = 'rgba(0, 166, 90, ' . $opacity . ')';
                break;
            case 'hahas':
                $color = 'rgba(57, 204, 204, ' . $opacity . ')';
                break;
            case 'sads':
                $color = 'rgba(0, 185, 183, ' . $opacity . ')';
                break;
            case 'angrys':
                $color = 'rgba(221, 75, 157, ' . $opacity . ')';
                break;
            case 'impressions':
                $color = 'rgba(216, 27, 96, ' . $opacity . ')';
                break;
            case 'total_video_views':
                $color = 'rgba(0, 192 ,239, ' . $opacity . ')';
                break;
            case 'total_video_views_autoplayed':
                $color = 'rgba(0, 166, 9, ' . $opacity . ')';
                break;
            case 'total_video_views_clicked_to_play':
                $color = 'rgba(96, 92 ,168, ' . $opacity . ')';
                break;
            case 'total_video_complete_views':
                $color = 'rgba(216, 27, 96, ' . $opacity . ')';
                break;
            case 'uniques':
            default:
                $color = 'rgba(0, 166 ,90, ' . $opacity . ')';
                break;
        }

        return $color;
    }

    /**
     * @param $metric
     * @return string
     */
    protected function getMetricLabel($metric)
    {
        switch ($metric) {
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
