<?php

namespace App\Http\Controllers;

use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ExportController extends Controller
{

    protected $post, $posts, $totalReactions, $totalReach, $totalVideos,
        $totalArticles, $totalEngagement, $totalLinkClicks, $filename, $totalShares, $totalLikes, $totalComments;

    /**
     * ExportController constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
            "Content-Disposition" => "attachment; filename=Insights_Unilad_".date('d-m-Y').".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $this->filename = 'insights_unilad_'.date('d-m-Y_h:m:s').'_.csv';
        $this->posts = $this->post->withTrashed();
        $this->posts = $this->posts->where('posted', '<', Carbon::now()->subDays(env('EXPORT_POSTED_LIMIT')));
        $this->posts = $this->posts->get();

        $this->totalReactions = $this->post->calculateTotal('reactions', $this->posts);
        $this->totalReach = $this->post->calculateTotal('reach', $this->posts);

        $this->totalVideos = array_count_values($this->posts->pluck('type')->toArray())['video'];
        $this->totalArticles = array_count_values($this->posts->pluck('type')->toArray())['link'];

        $this->totalEngagement = $this->post->calculateEngagement($this->posts);
        $this->totalLinkClicks = $this->totalComments = $this->totalLikes = $this->totalShares = 0;

        $this->buildCSV($this->filename);

        return response()->download(storage_path()."/exports/".$this->filename, $this->filename, $headers);
    }

    public function buildCSV($filename)
    {
        $file = fopen(storage_path()."/exports/".$filename, 'w');
        fputcsv($file, $this->post->exportHeadings);

        foreach($this->posts as $post) {
            $postEngagement = $post->shares + $post->likes + $post->comments;
            $percentOfEngagement = $postEngagement/$this->totalEngagement*100;
            $this->totalLinkClicks += $post->link_clicks;
            $this->totalShares += $post->latestStatSnapshot()->shares;
            $this->totalLikes += $post->latestStatSnapshot()->likes;
            $this->totalComments += $post->latestStatSnapshot()->comments;

            fputcsv($file, [
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
                'Primary Cat',
                'GA: views/clicks',
                'GA page views',
                'GA load speed',
                'GA bounce rate',
                round($percentOfEngagement, 1)
            ]);
        }

        fputcsv($file, $this->post->exportTotalHeadings);

        fputcsv($file, [
            $this->totalArticles,
            $this->totalVideos,
            '',
            '',
            '',
            $this->totalReach,
            $this->totalReactions,
            $this->totalShares,
            $this->totalLikes,
            $this->totalComments,
            $this->totalLinkClicks,
            $this->totalEngagement]);

        fclose($file);
    }
}
