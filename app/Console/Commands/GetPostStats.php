<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\Facebook;
use App\PostStatSnapshot;
use App\Post;

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
        $snapshot = new PostStatSnapshot;
        $postId = $this->argument('postid');
        $snapshot->facebook_id = $postId;
        $post = Post::where(['facebook_id' => $postId])->first();
        if ($post) {
            $snapshot->post_id = $post->id;
            
            try {
                // Like count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=LIKE&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->likes = $response->getDecodedBody()["summary"]["total_count"];

                // Love count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=LOVE&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->loves = $response->getDecodedBody()["summary"]["total_count"];
                
                // Wow count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=WOW&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->wows = $response->getDecodedBody()["summary"]["total_count"];
                
                // Haha count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=HAHA&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->hahas = $response->getDecodedBody()["summary"]["total_count"];
                
                // Sad count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=SAD&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->sads = $response->getDecodedBody()["summary"]["total_count"];
                
                // Angry count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=ANGRY&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->angrys = $response->getDecodedBody()["summary"]["total_count"];
                
                // Comment count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/comments/?summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->comments = $response->getDecodedBody()["summary"]["total_count"];

                // Share count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/?fields=shares', env('FACEBOOK_ACCESS_TOKEN'));
                $snapshot->shares = $response->getDecodedBody()["shares"]["count"];

                if ($post->type == 'video') {
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
            
            $snapshot->save();
        }
    }
}
