<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\Facebook;
use App\PostStatSnapshot;

class GetPostStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getpoststats {postid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets and saves a stats snapshot for a given post ID';

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
        $post = new PostStatSnapshot;
        $postId = $this->argument('postid');
        $post->facebook_id = $postId;
        $post->post_id = 0;

        // Like count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=LIKE&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
        $post->likes = $response->getDecodedBody()["summary"]["total_count"];

        // Love count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=LOVE&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
        $post->loves = $response->getDecodedBody()["summary"]["total_count"];
        
        // Wow count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=WOW&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
        $post->wows = $response->getDecodedBody()["summary"]["total_count"];
        
        // Haha count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=HAHA&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
        $post->hahas = $response->getDecodedBody()["summary"]["total_count"];
        
        // Sad count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=SAD&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
        $post->sads = $response->getDecodedBody()["summary"]["total_count"];
        
        // Angry count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=ANGRY&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
        $post->angrys = $response->getDecodedBody()["summary"]["total_count"];

        // Thankful count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=THANKFUL&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
        $post->thankfuls = $response->getDecodedBody()["summary"]["total_count"];
        
        // Comment count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/comments/?summary=1', env('FACEBOOK_ACCESS_TOKEN'));
        $post->comments = $response->getDecodedBody()["summary"]["total_count"];

        // Share count
        $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/?fields=shares', env('FACEBOOK_ACCESS_TOKEN'));
        $post->shares = $response->getDecodedBody()["shares"]["count"];        

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
        
        $post->impressions_nonviral = 0;
        $post->uniques_nonviral = 0;
        $post->save();
    }
}
