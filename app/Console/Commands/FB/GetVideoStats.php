<?php

namespace App\Console\Commands\FB;

use Illuminate\Console\Command;
use App\Facebook;
use App\VideoStatSnapshot;
use App\Post;

class GetVideoStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getvideostats {videoid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the video insights for a given video ID';

    /**
     * The simple (one-dimensional) video stat fields
     * 
     * @var array
     */
    protected $simpleFields = [
        'total_video_views',
        'total_video_views_unique',
        'total_video_views_autoplayed',
        'total_video_views_clicked_to_play',
        'total_video_views_organic',
        'total_video_views_organic_unique',
        'total_video_views_paid',
        'total_video_views_paid_unique',
        'total_video_views_sound_on',
        'total_video_complete_views',
        'total_video_complete_views_unique',
        'total_video_complete_views_autoplayed',
        'total_video_complete_views_clicked_to_play',
        'total_video_complete_views_organic',
        'total_video_complete_views_organic_unique',
        'total_video_complete_views_paid',
        'total_video_complete_views_paid_unique',
        'total_video_10s_views',
        'total_video_10s_views_unique',
        'total_video_10s_views_auto_played',
        'total_video_10s_views_clicked_to_play',
        'total_video_10s_views_organic',
        'total_video_10s_views_paid',
        'total_video_10s_views_sound_on',
        'total_video_avg_time_watched',
        'total_video_view_total_time',
        'total_video_view_total_time_organic',
        'total_video_view_total_time_paid',
        'total_video_impressions',
        'total_video_impressions_unique',
        'total_video_impressions_paid_unique',
        'total_video_impressions_paid',
        'total_video_impressions_organic_unique',
        'total_video_impressions_organic',
        'total_video_impressions_viral_unique',
        'total_video_impressions_viral',
        'total_video_impressions_fan_unique',
        'total_video_impressions_fan',
        'total_video_impressions_fan_paid_unique',
        'total_video_impressions_fan_paid',
		//todo total_video_retention_graph
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function handle()
    {
        $api = new Facebook;
        $snapshot = new VideoStatSnapshot;
        $postId = $this->argument('videoid');
        $post = Post::where(['facebook_id' => $postId])->first();
        if ($post) {
            if ($post->type !== 'video') {
                $this->error('Post is not a video');
                return;
            }
            $snapshot->post_id = $post->id;
        
            $response = $api->get('/' . $postId . '/video_insights', env('FACEBOOK_ACCESS_TOKEN'));
            if ($response) {
                foreach ($response->getGraphEdge() as $node) {
                    if (in_array($node["name"], $this->simpleFields)) {
                        $simpleStats[$node["name"]] = $node["values"][0]["value"];
                    }
                }
                $snapshot->fill($simpleStats);
                
                $snapshot->save();
            }
        }
    }
}
