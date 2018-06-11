<?php

namespace App\Http\Controllers;

use App\AverageMetric;
use App\Creator;
use App\Post;
use App\VideoLabel;
use App\Page;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PageController extends Controller
{
    protected
        $averageMetric,
        $articleImpressions,
        $articleReactions,
        $articleShares,
        $articleComments,
        $creator,
        $creatorPosts,
        $creatorFilter,
        $from,
        $iaFilter,
        $instantArticles,
        $label,
        $labelFilter,
        $page,
        $post,
        $query,
        $to,
        $type,
        $typeFilter,
        $videoImpressions,
        $videoReactions,
        $videoShares,
        $videoComments,
        $videoLabel;

    /**
     * PageController constructor.
     * @param Page $page
     * @param Post $post
     * @param AverageMetric $averageMetric
     * @param VideoLabel $videoLabel
     * @param Creator $creator
     */
    public function __construct(Page $page, Post $post, AverageMetric $averageMetric, VideoLabel $videoLabel, Creator $creator)
    {
        $this->page = $page;
        $this->post = $post;
        $this->averageMetric = $averageMetric;
        $this->videoLabel = $videoLabel;
        $this->creator = $creator;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $pages = $this->page->all();

        return view('pages.index', ['pages' => $pages]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $this->creatorPosts= request()->get('creator');
        $this->label = request()->get('label');
        $this->instantArticles = request()->get('ia');
        $this->type = request()->get('type');
        $this->from = request()->get('from') ? Carbon::parse(request()->get('from')) :            Carbon::now()->startOfDay();
        $this->to = request()->get('to') ?     Carbon::parse(request()->get('to'))->endOfDay() :  Carbon::now()->endOfDay();

        $page = $this->page->find($id);

        $this->query = $page->posts()->orderBy('posted', 'DESC');

        $this->getLabels();
        $this->getCreator();
        $this->getInstantArticles();
        $this->getPostType();
        $this->getDates();

        $daysInRange = $this->from->diffInDays($this->to) + 1;

        $dayPercentage = $this->to->isToday() ? ((date('H') + (($daysInRange - 1) * 24)) / (24 + (($daysInRange - 1) * 24)) + date('i') / (60 * 24)) : 1;

        $labels = $this->videoLabel->all();

        $this->query = $this->query->get();

        $this->processPosts($this->query);

        $averages = $this->averageMetric->all()->keyBy('key');

        return view('pages.show', [
            'articleComments' => $this->articleComments,
            'articleReach' => $this->articleImpressions,
            'articleReactions' => $this->articleReactions,
            'articleShares' => $this->articleShares,
            'averages' => $averages,
            'creatorFilter' => $this->creatorFilter,
            'daysInRange' => $daysInRange,
            'day_percentage' => $dayPercentage,
            'iaFilter' => $this->iaFilter,
            'labelFilter' => $this->labelFilter,
            'labels' => $labels,
            'pageId' => $id,
            'pageName' => $page->name,
            'posts' => $this->query,
            'type' => $this->type,
            'typeFilter' => $this->typeFilter,
            'videoComments' => $this->videoComments,
            'videoReach' => $this->videoImpressions,
            'videoReactions' => $this->videoReactions,
            'videoShares' => $this->videoShares,
            'from' => $this->from,
            'to' => $this->to
        ]);

    }

    /**
     * Accumulate all actions for every post retrieved from db
     * @param $query
     */
    private function processPosts(Collection $query) : void
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
     * Search query by labels stored along side video
     */
    private function getLabels() : void
    {
        if ($this->label) {
            $this->query = $this->post->whereHasEntity('videoLabels', $this->label);
            $this->labelFilter = $this->videoLabel->find($this->label);
        }
    }

    /**
     * Search query by user/creator
     */
    private function getCreator() : void
    {
        if ($this->creatorPosts) {
            $this->query = $this->post->whereHasEntity('creator', $this->creatorPosts);
            $this->creatorFilter = $this->creator->find($this->creatorPosts);
        }
    }

    /**
     * Search query for Instant Articles only
     */
    private function getInstantArticles() : void
    {
        if ($this->instantArticles) {
            $this->query->where('instant_article', true);
            $this->iaFilter = true;
        }
    }

    /**
     * Search query by type of post
     */
    private function getPostType() : void
    {
        if ($this->type) {
            $this->query->where('type', $this->type);
            $this->typeFilter = true;
        }
    }

    /**
     * Search query dates
     */
    private function getDates() : void
    {
        $this->query->whereBetween('posted', [$this->from->toDateTimeString(), $this->to->toDateTimeString()]);
    }

}
