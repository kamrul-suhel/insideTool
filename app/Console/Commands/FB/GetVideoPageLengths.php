<?php

namespace App\Console\Commands\FB;

use App\VideoStatSnapshot;
use Illuminate\Console\Command;
use App\Facebook;
use App\Creator;
use App\Page;
use App\Post;
use App\PublishedInstantArticle;
use App\VideoLabel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GetVideoPageLengths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getvideopagelengths {pageid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrieves the length for all video posts';

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

        $page = Page::where(['facebook_id' => $this->argument('pageid')])->first();

        $snapshotsWithRetention = VideoStatSnapshot::whereNotNull('total_video_retention_graph')->groupBy('post_id')->pluck('post_id');
        $videos = Post::where('type', 'video')->where('length', '=', 0.00)->whereNull('delete_at')->whereIn('id', $snapshotsWithRetention)->get();

        foreach($videos as $video) {
	        $videoLengthResponse = $api->get('/' . $video->facebook_id . '/?fields=length', $page->access_token);
	        if($videoLengthResponse) {
		        $video->length = $videoLengthResponse->getGraphNode()->getField('length');
		        $video->save();
	        }
        }
    }
}
