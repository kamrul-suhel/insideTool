<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Facebook;
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
                $reactions = 0;
                $comments = 0;
                $shares = 0;

                // Like count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=LIKE&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                if ($response) {
                    $likes = $response->getDecodedBody()["summary"]["total_count"];
                    $snapshot->likes = $likes;

                    if ($likes > 0) {
                        $post->likes = $likes;
                        $post->save(); 
                        $reactions += $likes;
                    }
                }

                // Love count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=LOVE&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                if ($response) {
                    $snapshot->loves = $response->getDecodedBody()["summary"]["total_count"];
                    $reactions += $snapshot->loves;
                }
                
                // Wow count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=WOW&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                if ($response) {
                    $snapshot->wows = $response->getDecodedBody()["summary"]["total_count"];
                    $reactions += $snapshot->wows;
                }
                
                // Haha count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=HAHA&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                if ($response) {
                    $snapshot->hahas = $response->getDecodedBody()["summary"]["total_count"];
                    $reactions += $snapshot->hahas;
                }
                
                // Sad count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=SAD&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                if ($response) {
                    $snapshot->sads = $response->getDecodedBody()["summary"]["total_count"];
                    $reactions += $snapshot->sads;
                }
                
                // Angry count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/reactions/?type=ANGRY&summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                if ($response) {
                    $snapshot->angrys = $response->getDecodedBody()["summary"]["total_count"];
                    $reactions += $snapshot->angrys;
                }
                
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/comments/?summary=1', env('FACEBOOK_ACCESS_TOKEN'));
                // Comment count
                if ($response) {
                    $comments = $response->getDecodedBody()["summary"]["total_count"];
                    $snapshot->comments = $comments;
                }

                if ($comments > 0) {
                    $post->comments = $comments;
                    $post->save();
                }

                if ($reactions > 0) {
                    $post->reactions = $reactions;
                    $post->save();
                }

                // Share count
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_'. $postId . '/?fields=shares', env('FACEBOOK_ACCESS_TOKEN'));
                if ($response && array_key_exists('shares', $response->getDecodedBody())) {
                    $shares = $response->getDecodedBody()["shares"]["count"];
                    $snapshot->shares = $shares;
                }

                if ($shares > 0) {
                    $post->shares = $shares;
                    $post->save();
                }

            } catch (\Facebook\Exceptions\FacebookResponseException $e) {
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
