<?php

namespace App\Console\Commands\FB;

use App\Comment;
use App\Facebook;
use App\Post;
use App\PostStatSnapshot;
use Facebook\Exceptions\FacebookResponseException;
use Illuminate\Console\Command;

class GetComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getcomments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get comments for all posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $api = new Facebook;
        $snapshot = new PostStatSnapshot;

        $posts = Post::all();

        foreach($posts as $post) {
            try {
                $response = $api->get('/' . env('FACEBOOK_PAGE_ID') . '_' . $post->id . '/comments/?limit=250&order=reverse_chronological', env('FACEBOOK_ACCESS_TOKEN'));

                $comments = $response->getDecodedBody()['data'];

                foreach ($comments as $comment) {
                    Comment::updateOrCreate([
                        'facebook_id' => $post->id,
                        'comment_id' => $comment['id'],
                        'comment' => $comment['message'],
                        'created_at' => date('Y-m-d h:i:s', strtotime($comment['created_time'])),
                    ]);
                }
            } catch (FacebookResponseException $e) {
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
