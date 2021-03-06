<?php

namespace App\Classes;

use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Export
{
    /**
     * @var Analytics
     */
    protected $analytics;
    /**
     * @var Post
     */
    protected $post;

	/**
	 * @var
	 */
    protected $postIds;

    /**
     * @var Post
     */
    protected $posts;
    /**
     * @var
     */
    protected $totalReactions;
    /**
     * @var
     */
    protected $totalReach;
    /**
     * @var
     */
    protected $totalVideos;
    /**
     * @var
     */
    protected $totalArticles;
    /**
     * @var
     */
    protected $totalEngagement;
    /**
     * @var
     */
    protected $totalLinkClicks;
    /**
     * @var
     */
    protected $filename;
    /**
     * @var
     */
    protected $totalShares;
    /**
     * @var
     */
    protected $totalLikes;
    /**
     * @var
     */
    protected $totalComments;
    /**
     * @var
     */
    protected $from;
    /**
     * @var
     */
    protected $to;

    /**
     * Export constructor.
     * @param Post $post
     * @param Analytics $analytics
     */
    public function __construct(Post $post, Analytics $analytics)
    {
        $this->post = $post;
        $this->analytics = $analytics;
    }

	/**
	 * @param null $from
	 * @param null $to
	 * @return bool
	 */
    public function export($from = null, $to = null)
    {
        //set dates
        $this->setExportDates($from, $to);

        //set filename
        $this->setFilename();

        //gather posts
        $this->getPosts();

        if($this->posts->count() < 1) {
            return false;
        }

        //link fb data with posts
        $this->getFBData();

        //build new csv
        $this->buildCSV($this->filename);

        return $this->filename;
    }

    /**
     * Set dates for export
	 * @param $from
	 * @param $to
	 */
    public function setExportDates($from, $to): void
	{
		if (is_null($from) && is_null($to)) {
			$this->from = Carbon::now()->subDays(env('EXPORT_POSTED_LIMIT') + 4)->startOfDay();
			$this->to = Carbon::now()->subDays(env('EXPORT_POSTED_LIMIT'))->endOfDay();
		} else {
			$this->from = $from;
			$this->to = $to;
		}
	}

    /**
     * Set name of file produced by export
     */
    public function setFilename(): void
    {
        $this->filename = 'insights_unilad_' . date('d-m-Y_h:m:s') . '.csv';
    }

    /**
     * Get al posts within the date ranges provided
     */
    public function getPosts(): void
    {
        $this->posts = $this->post->withTrashed();
        $this->posts = $this->posts->whereBetween('posted', [$this->from, $this->to])->get();
    }

    /**
     * Get FB analytics data for posts
     * Reactions, Reach, Total Vids, and Articles, Engagement, Link Clicks
     */
    public function getFBData(): void
    {
        $this->totalReactions = $this->post->calculateTotal('reactions', $this->posts);
        $this->totalReach = $this->post->calculateTotal('reach', $this->posts);
		$this->totalShares = $this->post->calculateTotal('shares', $this->posts);
		$this->totalLikes = $this->post->calculateTotal('likes', $this->posts);
		$this->totalComments = $this->post->calculateTotal('comments', $this->posts);

        $this->totalVideos = array_count_values($this->posts->pluck('type')->toArray())['video'];
        $this->totalArticles = array_count_values($this->posts->pluck('type')->toArray())['link'];
        $this->totalIAArticles = array_count_values($this->posts->where('instant_article', 1)->pluck('type')->toArray())['link'];
        $this->totalNonIAArticles = array_count_values($this->posts->where('instant_article', 0)->pluck('type')->toArray())['link'];

        $this->totalEngagement = $this->post->calculateEngagement($this->posts);
    }

    /**
     * Format row for a post to add to csv
     * @param $post
     * @return array
     */
    public function formatPostData(Post $post): array
    {
        $postEngagement = $post->shares + $post->likes + $post->comments;
        $percentOfEngagement = $postEngagement / $this->totalEngagement * 100;
        $this->totalLinkClicks += $post->link_clicks;

        $postArray = [
            '"'.$post->facebook_id.'"', //with quotes so it doesn't return exponent number to xls
            $post->creator->name,
            $post->link,
            $post->message,
            $post->posted,
            implode(', ', $post->videoLabels()->pluck('label')->toArray()),
            $post->deleted_at,
            $post->reach,
            $post->reactions,
            $post->shares,
            $post->likes,
            $post->comments,
            $post->link_clicks,
            $postEngagement,
            $post->type,
			$post->instant_article,
            $post->ga_avg_time_on_page,
            $post->ga_page_views,
            $post->ga_avg_page_load_time,
            $post->ga_bounce_rate,
            round($percentOfEngagement, 1)
        ];
        return $postArray;
    }

    /**
     * Format totals on bottom row for csv
     * @return array
     */
    public function formatTotals(): array
    {
        return [
            '', '', '', '', //blank columns
            $this->totalIAArticles,
            $this->totalNonIAArticles,
            $this->totalVideos,
            $this->totalReach,
            $this->totalReactions,
            $this->totalShares,
            $this->totalLikes,
            $this->totalComments,
            $this->totalLinkClicks,
            $this->totalEngagement
        ];
    }

    /**
     * Build new csv with required rows, and columns
     * @param $filename
     */
    public function buildCSV($filename): void
    {
        if (!file_exists(storage_path("app/exports/"))) {
            mkdir(storage_path("app/exports/"));
        }

        //get and open new csv to writing
        $file = fopen(storage_path("app/exports/" . $filename), 'w');

        //Set headings
        fputcsv($file, $this->post->exportHeadings);

        //For every post apply new row to csv
		Log::info($this->posts->count());
		$count = 0;
        foreach ($this->posts as $post) {
            fputcsv($file, $this->formatPostData($post));
            $count += 1;
            Log::info('Counter:: '. $count);
        }

        //Set Totals row
        fputcsv($file, $this->post->exportTotalHeadings);
        fputcsv($file, $this->formatTotals());

        //close csv
        fclose($file);
    }

}