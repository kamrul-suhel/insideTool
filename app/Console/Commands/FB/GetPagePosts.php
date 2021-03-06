<?php

namespace App\Console\Commands\FB;

use Illuminate\Console\Command;
use App\Facebook;
use App\Creator;
use App\Page;
use App\Post;
use App\PublishedInstantArticle;
use App\VideoLabel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function handle()
    {
        $api = new Facebook;
        if ($this->argument('limit')) {
            $limit = $this->argument('limit');
        } else {
			$limit = 5;
        }
        $page = Page::where(['facebook_id' => $this->argument('pageid')])->first();
        $response = $api->get('/' . $this->argument('pageid') . '/posts/?limit=' . $limit, $page->access_token);
        if ($response) {
            foreach ($response->getGraphEdge() as $node) {
                $postResponse = $api->get('/' . $node->getField('id') . '?fields=story,message,name,link,picture,type,created_time,object_id,admin_creator,parent_id', $page->access_token);
                if ($postResponse) {
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

                    if ($parentId = $postResponse->getGraphNode()->getField('parent_id')) {
                        $post->parent_id = $parentId;
                        $post->message = $postResponse->getGraphNode()->getField('story');
                    }

                    if ($post->picture) {
                        $image = file_get_contents($post->picture);
                        $filename = uniqid('post_') . '.jpg';
                        Storage::disk('public')->put('post_images/' . $filename, $image);
                        $post->picture = asset('storage/post_images/' . $filename);
                    }

                    $post->type = $postResponse->getGraphNode()->getField('type');
                    $post->posted = $postResponse->getGraphNode()->getField('created_time');

                    $adminCreator = $postResponse->getGraphNode()->getField('admin_creator');
                    if ($adminCreator) {
                        $creator = Creator::firstOrNew(['facebook_id' => $adminCreator["id"]]);
                        $creator->name = $adminCreator["name"];
                    } else {
                        $creator = Creator::firstOrNew(['facebook_id' => 0]);
                        $creator->name = "UNILAD";
                    }
                    $creator->save();
                    $post->creator_id = $creator->id;

                    $post->save();
                    $objectId = $postResponse->getGraphNode()->getField('object_id');

                    // VIDEO - GET CUSTOM LABELS BASED ON VIDEOS THAT HAVE BEEN FOUND
                    if ($post->type == 'video' && $objectId) {
                        $videoResponse = $api->get('/' . $objectId . '/?fields=custom_labels', $page->access_token);
                        if ($videoResponse) {
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

                        $videoResponse = $api->get('/' . $objectId . '/?fields=length', $page->access_token);
                        if($videoResponse) {
	                        $post->length = $videoResponse->getGraphNode()->getField('length');
	                        $post->save();
                        }

                    // LINKS - GET INSTANT ARTICLE
                    } else if ($post->type == 'link') {
                        $instantArticles = $api->get('/' . $this->argument('pageid') . '/instant_articles', $page->access_token);
                        if ($instantArticles) {
                            foreach ($instantArticles->getGraphEdge() as $article) {
                                $article = $api->get('/' . $article->getField('id') . '/?fields=publish_status,canonical_url', $page->access_token);
                                if ($article && $article->getGraphNode()->getField('publish_status') == 'LIVE') {
                                    $published = PublishedInstantArticle::firstOrNew(['facebook_id' => $article->getGraphNode()->getField('id')]);
                                    $published->canonical_url = $article->getGraphNode()->getField('canonical_url');
                                    $published->save();
                                }
                            }
                            $instantArticle = PublishedInstantArticle::where('canonical_url', $post->link)->first();

                            if ($instantArticle) {
                                $post->instant_article = true;
                                $post->save();
                            }
                        }
                    }
                    
                    if ($newPost) {
                        // Immediately pull stats
                        \Artisan::call('stats:getpoststats', ['postid' => $postId]);
                        \Artisan::call('stats:getpoststatsdelayed', ['postid' => $postId]);
                        \Artisan::call('stats:getgapoststats', ['postid' => $postId]); // Ga Analytics
                    }
                }
            }
        }
    }
}
