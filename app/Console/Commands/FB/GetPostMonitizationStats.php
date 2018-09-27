<?php

namespace App\Console\Commands\FB;

use App\Classes\Analytics;
use App\VideoMonitizationStatSnapshot;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Facebook;
use App\PostStatSnapshot;
use App\Post;

class GetPostMonitizationStats extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'stats:getpostmonitizationstats {postid}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gets and saves a monitization stats snapshot for a given post ID';

	/**
	 * @var Analytics
	 */
	protected $analytics;

	/**
	 * GetPostStats constructor.
	 * @param Analytics $analytics
	 */
	public function __construct(Analytics $analytics)
	{
		parent::__construct();

		$this->analytics = $analytics;
	}

	/**
	 * @throws \Facebook\Exceptions\FacebookResponseException
	 * @throws \Facebook\Exceptions\FacebookSDKException
	 */
	public function handle()
	{
		$api = new Facebook;
		$snapshot = new VideoMonitizationStatSnapshot();
		$postId = $this->argument('postid');
		$snapshot->post_id = $postId;
		$post = Post::where(['facebook_id' => $postId])->first();

		if ($post) {
			try {
				$snapshot->post_id = $post->id;
				$response = $api->get('/' . $post->page->facebook_id . '_' . $postId . '/insights?metric=post_video_ad_break_ad_impressions&period=lifetime', $post->page->access_token);
				if ($response) {
					$postVideoAdBreakAdImpression = $response->getGraphEdge()[0]["values"][0]["value"];
					$snapshot->post_video_ad_break_ad_impressions = $postVideoAdBreakAdImpression;
				}

				$snapshot->post_id = $post->id;
				$response = $api->get('/' . $post->page->facebook_id . '_' . $postId . '/insights?metric=post_video_ad_break_earnings&period=lifetime', $post->page->access_token);
				if ($response) {
					$postVideoAdBreakEarnings = $response->getGraphEdge()[0]["values"][0]["value"];
					$snapshot->post_video_ad_break_earnings = $postVideoAdBreakEarnings;
				}

				$snapshot->post_id = $post->id;
				$response = $api->get('/' . $post->page->facebook_id . '_' . $postId . '/insights?metric=post_video_ad_break_ad_cpm&period=lifetime', $post->page->access_token);
				if ($response) {
					$postVideoAdBreakAdCpm = $response->getGraphEdge()[0]["values"][0]["value"];
					$snapshot->post_video_ad_break_ad_cpm = $postVideoAdBreakAdCpm;
				}

				if($snapshot->post_video_ad_break_ad_impressions == 0
					&& $snapshot->post_video_ad_break_earnings == 0
					&& $snapshot->post_video_ad_break_ad_cpm == 0) {

					$snapshot->delete();
					$post->ads = 0;
					$post->save();
				} else {
					$snapshot->save();
				}

			} catch (\Facebook\Exceptions\FacebookResponseException $e) {
				if ($e->getCode() == 100 && $e->getSubErrorCode() == 33) {
//					Post has been deleted
                    $post->delete();
                } else {
					throw $e;
				}
			}
		}
	}
}