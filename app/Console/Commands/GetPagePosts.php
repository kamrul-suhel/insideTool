<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\Facebook;
use App\Post;
use App\Page;

class GetPagePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getpageposts {pageid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the latest posts for a given page ID';

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
        $response = $api->get('/' . $this->argument('pageid') . '/posts/?limit=5', env('FACEBOOK_ACCESS_TOKEN'));
        foreach ($response->getGraphEdge() as $node) {
            $postResponse = $api->get('/' . $node->getField('id') . '?fields=message,name,link,picture,type,created_time', env('FACEBOOK_ACCESS_TOKEN'));
            $postId = explode("_", $postResponse->getGraphNode()->getField('id'))[1];
            $newPost = false;
            $post = Post::withTrashed()->where('facebook_id', $postId)->first();
            if (!$post) {
                $post = new Post;
                $post->facebook_id = $postId;
                $newPost = true;
            }
            $page = Page::where('facebook_id', $this->argument('pageid'))->firstOrFail();            
            $post->page_id = $page->id;
            $post->message = $postResponse->getGraphNode()->getField('message');
            $post->name = $postResponse->getGraphNode()->getField('name');
            $post->link = $postResponse->getGraphNode()->getField('link');
            $post->picture = $postResponse->getGraphNode()->getField('picture');
            $post->type = $postResponse->getGraphNode()->getField('type');
            $post->posted = $postResponse->getGraphNode()->getField('created_time');
            $post->save();

            if ($newPost) {
                // Immediately pull stats
                \Artisan::call('stats:getpoststats', ['postid' => $postId]);
                \Artisan::call('stats:getpoststatsdelayed', ['postid' => $postId]);
            }
        }

    }
}
