<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Facebook\Facebook;
use App\Creator;
use App\Page;
use App\Post;
use App\VideoLabel;

class GetPagePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getpageposts {pageid} {limit?}';

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
        if ($this->argument('limit')) {
            $limit = $this->argument('limit');
        } else {
            $limit = 5;
        }
        $response = $api->get('/' . $this->argument('pageid') . '/posts/?limit=' . $limit, env('FACEBOOK_ACCESS_TOKEN'));
        foreach ($response->getGraphEdge() as $node) {
            $postResponse = $api->get('/' . $node->getField('id') . '?fields=message,name,link,picture,type,created_time,object_id,admin_creator', env('FACEBOOK_ACCESS_TOKEN'));
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

            $adminCreator = $postResponse->getGraphNode()->getField('admin_creator');
            $creator = Creator::firstOrNew(['facebook_id' => $adminCreator["id"]]);
            $creator->name = $adminCreator["name"];
            $creator->save();
            $post->creator_fbid = $creator->facebook_id;

            $post->save();
            $objectId = $postResponse->getGraphNode()->getField('object_id');

            if ($post->type == 'video' && $objectId) {
                $videoResponse = $api->get('/' . $objectId . '/?fields=custom_labels', env('FACEBOOK_ACCESS_TOKEN'));
                foreach ($videoResponse->getGraphNode() as $node) {
                    if (is_object($node)) {
                        foreach ($node as $label) {
                            $label = VideoLabel::firstOrCreate(['label' => $label]);
                            if (!$post->videoLabels->contains($label->id)) {
                                $post->videoLabels()->attach($label);
                            }
                        }
                    }
                }
            }
            
            if ($newPost) {
                // Immediately pull stats
                \Artisan::call('stats:getpoststats', ['postid' => $postId]);
                \Artisan::call('stats:getpoststatsdelayed', ['postid' => $postId]);
            }
        }

    }
}
