<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Creator;
use App\Post;
use App\PostStatSnapshot;
use App\VideoStatSnapshot;
use App\VideoLabel;
use App\PostDelayedStatSnapshot;
use App\AverageMetric;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;
use Yajra\Datatables\Datatables;

class PostController extends Controller
{
    protected
        $articleImpressions,
        $articleReactions,
        $articleShares,
        $articleComments,
        $creator,
        $creatorFilter,
        $from,
        $iaFilter,
        $instantArticles,
        $label,
        $labelFilter,
        $post,
        $query,
        $to,
        $type,
        $typeFilter,
        $videoImpressions,
        $videoReactions,
        $videoShares,
        $videoComments;

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
        $this->creator = request()->get('creator');
        $this->label = request()->get('label');
        $this->instantArticles = request()->get('ia');
        $this->type = request()->get('type');
        $this->from = request()->get('from') ? \Carbon\Carbon::parse(request()->get('from')) :            \Carbon\Carbon::now()->startOfDay();
        $this->to = request()->get('to') ?     \Carbon\Carbon::parse(request()->get('to'))->endOfDay() :  \Carbon\Carbon::now()->endOfDay();

        //TODO - Move into search class (new class for search functionality
        $this->query = $this->post->getAllPosts();

        $this->getLabels();
        $this->getCreator();
        $this->getInstantArticles();
        $this->getPostType();
        $this->getDates();

        $daysInRange = $this->from->diffInDays($this->to) + 1;

        $labels = VideoLabel::all();

        $this->query = $this->query->get();

        $this->processPosts($this->query);

        $averages = AverageMetric::all()->keyBy('key');

        return view('posts.index', [
            'posts' => $this->query,
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
     * Search query by labels stored along side video
     */
    public function getLabels() : void
    {
        if ($this->label) {
            $this->query = $this->post->whereHasEntity('videoLabels', $this->label);
            $this->labelFilter = VideoLabel::find($this->label);
        }
    }

    /**
     * Search query by user/creator
     */
    public function getCreator() : void
    {
        if ($this->creator) {
            $this->query = $this->post->whereHasEntity('creator', $this->creator);
            $this->creatorFilter = Creator::find($this->creator);
        }
    }

    /**
     * Search query for Instant Articles only
     */
    public function getInstantArticles() : void
    {
        if ($this->instantArticles) {
            $this->query->where('instant_article', true);
            $this->iaFilter = true;
        }
    }

    /**
     * Search query by type of post
     */
    public function getPostType() : void
    {
        if ($this->type) {
            $this->query->where('type', $this->type);
            $this->typeFilter = true;
        }
    }

    /**
     * Search query dates
     */
    public function getDates() : void
    {
        $this->query->whereBetween('posted', [$this->from->toDateTimeString(), $this->to->toDateTimeString()]);
    }

    /**
     * Accumulate all actions for every post retrieved from db
     * @param $query
     */
    public function processPosts(Collection $query) : void
    {
        foreach ($query as $post) {
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
     * Index js datatable values
     * @return mixed
     * @throws \Exception
     */
    public function indexDatatables() : Datatables
    {
        $query = Post::withTrashed()
            ->orderBy('posted', 'desc')
            ->with(['page', 'creator']);
        return Datatables::of($query)
            ->make(true);
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

        $averages = AverageMetric::all()->keyBy('key');
        return view('posts.show', ['post' => $post, 'liveLatest' => $latestStats, 'delayedLatest' => $latestDelayedStats,
            'lastBirthStats' => $birthStats, 'averages' => $averages]);
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
