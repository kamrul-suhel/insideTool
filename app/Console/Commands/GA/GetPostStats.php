<?php

namespace App\Console\Commands\GA;

use App\Classes\Analytics;
use Illuminate\Console\Command;
use App\Post;
use Illuminate\Support\Facades\Log;

class GetPostStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getgapoststats {postid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets and saves a GA stats to posts table';

    /**
     * @var Analytics
     */
    protected $analytics;

    /**
     * GetPostStats constructor.
     * @param Analytics $analytics
     */
    public function __construct(Analytics $analytics)
    {
        parent::__construct();

        $this->analytics = $analytics;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $postId = $this->argument('postid');
        $post = Post::where(['facebook_id' => $postId])->first();
        if ($post) {
            try {
                //get GA STATS
                $from = new \Carbon\Carbon("48 hours ago");
                $to = new \Carbon\Carbon("now");

                $link = str_replace('https://www.unilad.co.uk', '', $post->link);
                $gaStats = $this->analytics->fetchPostGAData($link, $from, $to);
                $post->ga_page_views = round($gaStats['ga:pageviews'], 1);
                $post->ga_avg_time_on_page = round($gaStats['ga:avgTimeOnPage'], 1);
                $post->ga_bounce_rate = round($gaStats['ga:bounceRate'], 1);
                $post->ga_avg_page_load_time = round($gaStats['ga:avgPageLoadTime'], 1);

                $post->save();

            } catch (\Exception $e) {
                Log::info(print_r('GA quota reached'));
            }
        }
    }
}
