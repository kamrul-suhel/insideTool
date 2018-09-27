<?php

namespace App\Console\Commands\FB;

use Illuminate\Console\Command;
use App\Post;

class GetStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:get {{--post_type=}} {{--type=}} {{--from=}} {{--to=}}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches stats for all posts in the database posted in the given date range';

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
        $from = new \Carbon\Carbon($this->option('from'));
        $to = new \Carbon\Carbon($this->option('to'));
        $type = $this->option('type');
        $postType = $this->option('post_type');

        if ($type == 'live') {
	        $posts = Post::whereBetween('posted', [$from, $to])->orderBy('id', 'DESC')->get();
            foreach ($posts as $post) {
                \Artisan::call('stats:getpoststats', ['postid' => $post->facebook_id]);
                \Artisan::call('stats:getgapoststats', ['postid' => $post->facebook_id]); // Ga Analytics
            }
        } else if ($type == 'delayed') {
	        $posts = Post::whereBetween('posted', [$from, $to])->orderBy('id', 'DESC')->get();
	        foreach ($posts as $post) {
		        \Artisan::call('stats:getpoststatsdelayed', ['postid' => $post->facebook_id]);
	        }
        } else if ($type == 'monitization') {
	        $posts = Post::whereBetween('posted', [$from, $to])->where('type', $postType)->where('ads', 1)->orderBy('id', 'DESC')->get();
        	foreach($posts as $post) {
                \Artisan::call('stats:getpostmonitizationstats', ['postid' => $post->facebook_id]);
	        }
        } else {
            $this->error("Invalid stats type, valid types are live and delayed");
        }
    }
}
