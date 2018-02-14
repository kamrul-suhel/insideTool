<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\Facebook;
use App\PostDelayedStatSnapshot;
use App\Post;

class GetPostStatsDelayed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getpoststatsdelayed {postid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets and saves a snapshot of the delayed stats for a given post ID';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $api = new Facebook;
        $snapshot = new PostDelayedStatSnapshot;
        $postId = $this->argument('postid');
        $snapshot->facebook_id = $postId;
        $post = Post::where(['facebook_id' => $postId])->first();
        if ($post) {
            $snapshot->post_id = $post->id;

            try {
                // Impressions
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->impressions = $response->getGraphEdge()[0]["values"][0]["value"];
                
                // Unique impressions
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_unique', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->uniques = $response->getGraphEdge()[0]["values"][0]["value"];

                // Fan impressions
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_fan', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->fan_impressions = $response->getGraphEdge()[0]["values"][0]["value"];
                
                // Fan uniques
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_fan_unique', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->fan_uniques = $response->getGraphEdge()[0]["values"][0]["value"];

                // Viral impressions
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_viral', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->impressions_viral = $response->getGraphEdge()[0]["values"][0]["value"];

                // Viral uniques
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_viral_unique', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->uniques_viral = $response->getGraphEdge()[0]["values"][0]["value"];
                
                $snapshot->save();
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                if ($e->getCode() == 100 && $e->getSubErrorCode() == 33) {
                    // Post has been deleted
                    $post->delete();
                }
            }
        }
    }
}
