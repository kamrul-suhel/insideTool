<?php

namespace App\Console\Commands\FB;

use Illuminate\Console\Command;
use App\Page;

class GetPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:getposts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the most recent posts for all pages';

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
        $pages = Page::all();
        foreach ($pages as $page) {
            \Artisan::call('stats:getpageposts', ['pageid' => $page->facebook_id]);
        }
    }
}
