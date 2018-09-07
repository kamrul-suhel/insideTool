<?php

namespace App\Console\Commands\FB;

use Illuminate\Console\Command;
use App\Facebook;
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
     * @throws \Facebook\Exceptions\FacebookResponseException
     * @throws \Facebook\Exceptions\FacebookSDKException
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
                $response = $api->get('/' . $post->page->facebook_id . '_'. $postId . '/insights/post_impressions', $post->page->access_token);
                if ($response) {
                    $snapshot->impressions = $response->getGraphEdge()[0]["values"][0]["value"];
                    $post->reach = $snapshot->impressions;
                    $post->save();
                }
                
                // Unique impressions
                $response = $api->get('/' . $post->page->facebook_id . '_'. $postId . '/insights/post_impressions_unique', $post->page->access_token);
                if ($response) {
                    $snapshot->uniques = $response->getGraphEdge()[0]["values"][0]["value"];
                }

                // Viral impressions
                $response = $api->get('/' . $post->page->facebook_id . '_'. $postId . '/insights/post_impressions_viral', $post->page->access_token);
                if ($response) {
                    $snapshot->impressions_viral = $response->getGraphEdge()[0]["values"][0]["value"];
                }

                // Viral uniques
                $response = $api->get('/' . $post->page->facebook_id . '_'. $postId . '/insights/post_impressions_viral_unique', $post->page->access_token);
                if ($response) {
                    $snapshot->uniques_viral = $response->getGraphEdge()[0]["values"][0]["value"];
                }

                // Link clicks
                $response = $api->get('/' . $post->page->facebook_id . '_'. $postId . '/insights/post_clicks_by_type/lifetime', $post->page->access_token);
                if ($response) {
                    $link_clicks = $response->getGraphEdge()[0]->getField('values')->getField(0)->getField('value');
                    if ($link_clicks) {
                        $snapshot->link_clicks = $link_clicks['link clicks'] ?? 0;
                        $post->link_clicks = $link_clicks['link clicks'] ?? 0;
                        $post->save();
                    }
                }

                $snapshot->impressions_paid = 0;
                $snapshot->uniques_paid = 0;
                
                $snapshot->save();

                if ($post->type == 'video' && !$post->parent_id) {
                    \Artisan::call('stats:getvideostats', ['videoid' => $post->facebook_id]);
                }
            } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                if ($e->getCode() == 100 && $e->getSubErrorCode() == 33) {
                    // Post has been deleted
                    $post->delete();
                } else {
                    throw $e;
                }
            }
        }
    }
}
