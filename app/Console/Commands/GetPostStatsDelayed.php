<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\Facebook;
use App\PostDelayedStatSnapshot;

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
        $post = new PostDelayedStatSnapshot;
        $postId = $this->argument('postid');
        $post->facebook_id = $postId;
        $post->post_id = 0;

        // Impressions
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions', env('FACEBOOK_ACCESS_TOKEN'));
        $post->impressions = $response->getGraphEdge()[0]["values"][0]["value"];

        // Paid impressions
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_paid', env('FACEBOOK_ACCESS_TOKEN'));
        $post->impressions_paid = $response->getGraphEdge()[0]["values"][0]["value"];
        
        // Unique impressions
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_unique', env('FACEBOOK_ACCESS_TOKEN'));
        $post->uniques = $response->getGraphEdge()[0]["values"][0]["value"];
        
        // Paid unique impressions
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_paid_unique', env('FACEBOOK_ACCESS_TOKEN'));
        $post->uniques_paid = $response->getGraphEdge()[0]["values"][0]["value"];

        // Fan impressions
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_fan', env('FACEBOOK_ACCESS_TOKEN'));
        $post->fan_impressions = $response->getGraphEdge()[0]["values"][0]["value"];

        // Paid fan impressions
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_fan_paid', env('FACEBOOK_ACCESS_TOKEN'));
        $post->fan_impressions_paid = $response->getGraphEdge()[0]["values"][0]["value"];
        
        // Fan uniques
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_fan_unique', env('FACEBOOK_ACCESS_TOKEN'));
        $post->fan_uniques = $response->getGraphEdge()[0]["values"][0]["value"];

        // Fan paid uniques
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_fan_paid_unique', env('FACEBOOK_ACCESS_TOKEN'));
        $post->fan_uniques_paid = $response->getGraphEdge()[0]["values"][0]["value"];

        // Organic impressions
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_organic', env('FACEBOOK_ACCESS_TOKEN'));
        $post->impressions_organic = $response->getGraphEdge()[0]["values"][0]["value"];

        // Organic uniques
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_organic_unique', env('FACEBOOK_ACCESS_TOKEN'));
        $post->uniques_organic = $response->getGraphEdge()[0]["values"][0]["value"];
        
        // Viral impressions
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_viral', env('FACEBOOK_ACCESS_TOKEN'));
        $post->impressions_viral = $response->getGraphEdge()[0]["values"][0]["value"];

        // Viral uniques
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/insights/post_impressions_viral_unique', env('FACEBOOK_ACCESS_TOKEN'));
        $post->uniques_viral = $response->getGraphEdge()[0]["values"][0]["value"];
        
        $post->save();
    }
}
