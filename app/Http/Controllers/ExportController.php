<?php

namespace App\Http\Controllers;

use App\Post;
use Carbon\Carbon;
use App\Classes\Analytics;
use Illuminate\View\View;

class ExportController extends Controller
{
    protected $analytics, $post, $posts, $totalReactions, $totalReach, $totalVideos,
        $totalArticles, $totalEngagement, $totalLinkClicks, $filename,
        $totalShares, $totalLikes, $totalComments, $from, $to;

    /**
     * ExportController constructor.
     * @param Post $post
     * @param Analytics $analytics
     */
    public function __construct(Post $post, Analytics $analytics)
    {
        $this->post = $post;

        $this->analytics = $analytics;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('exports.index');
    }

    /**
     * Export Data that is over 2 days old to CSV
     * @return mixed
     */
    public function export()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Insights_Unilad_" . date('d-m-Y') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        //set dates
        $this->setExportDates();

        //set filename
        $this->setFilename();

        //gather posts
        $this->getPosts();

        //link fb data with posts
        $this->getFBData();

        //build new csv
        $this->buildCSV($this->filename);

        //download csv
        return response()->download(storage_path() . "/exports/" . $this->filename, $this->filename, $headers);
    }

    /**
     * Set dates for export
     */
    public function setExportDates(): void
    {
        $this->from = Carbon::now()->subDays(env('EXPORT_POSTED_LIMIT') + 4)->endOfDay();
        $this->to = Carbon::now()->subDays(env('EXPORT_POSTED_LIMIT'))->startOfDay();
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

        $this->totalVideos = array_count_values($this->posts->pluck('type')->toArray())['video'];
        $this->totalArticles = array_count_values($this->posts->pluck('type')->toArray())['link'];

        $this->totalEngagement = $this->post->calculateEngagement($this->posts);
        $this->totalLinkClicks = $this->totalComments = $this->totalLikes = $this->totalShares = 0;
    }

    /**
     * @param $link
     * @return mixed
     */
    public function getGAData($link): array
    {
        $link = str_replace('https://www.unilad.co.uk', '', $link);
        return $gaResults = $this->analytics->fetchPostGAData($link, $this->from, $this->to);
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
        $this->totalShares += $post->latestStatSnapshot()->shares;
        $this->totalLikes += $post->latestStatSnapshot()->likes;
        $this->totalComments += $post->latestStatSnapshot()->comments;

        $gaResults = $this->getGAData($post->link);

        $postArray = [
            '"'.$post->facebook_id.'"', //with quotes so it doesn't return exponent number to xls
            $post->creator->name,
            $post->link,
            $post->message,
            $post->posted,
            $post->deleted_at,
            $post->reach,
            $post->reactions,
            $post->latestStatSnapshot()->shares,
            $post->latestStatSnapshot()->likes,
            $post->latestStatSnapshot()->comments,
            $post->link_clicks,
            $postEngagement,
            $post->type,
            round($gaResults['ga:avgTimeOnPage'],   1),
            round($gaResults['ga:pageviews']),
            round($gaResults['ga:avgPageLoadTime'], 1),
            round($gaResults['ga:bounceRate'],      1),
            round($percentOfEngagement,             1)
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
            $this->totalArticles,
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
        //get and open new csv to writing
        $file = fopen(storage_path() . "/exports/" . $filename, 'w');

        //Set headings
        fputcsv($file, $this->post->exportHeadings);

        //For every post apply new row to csv
        foreach ($this->posts as $post) {
            fputcsv($file, $this->formatPostData($post));
        }

        //Set Totals row
        fputcsv($file, $this->post->exportTotalHeadings);
        fputcsv($file, $this->formatTotals());

        //close csv
        fclose($file);
    }
}
