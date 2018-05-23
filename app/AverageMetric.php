<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AverageMetric extends Model
{
    /**
     * @var array
     */
    public $fillable = ['key'];

    /**
     * Update all averages
     */
    public static function updateAverages(): void
    {
        self::getLifetimeAverages();
        self::getBirthStatsAverages();
        self::getOverallAverages();
        self::getDailyAverages();
    }

    /**
     * Round and save metric to db
     * @param $key
     * @param $category
     */
    public static function roundMetric($key, $category)
    {
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => $key]);
        $metric->average = round($category);
        $metric->save();
    }

    /**
     * Round all Lifetime averages
     */
    public static function getLifetimeAverages() : void
    {
        // Lifetime averages
        $query = __('metrics.lifetime_averages');
        $result = \DB::select($query);
        $result = $result[0];

        // Likes per minute (lifetime)
        self::roundMetric('likes_perminute_lifetime', $result->likespm);

        // Shares per minute (lifetime)
        self::roundMetric('shares_perminute_lifetime', $result->sharespm);

        // Comments per minute (lifetime)
        self::roundMetric('comments_perminute_lifetime', $result->commentspm);

        // Reactions per minute (lifetime)
        self::roundMetric('reactions_perminute_lifetime', $result->reactionspm);

        // Lifetime averages (video)
        $query = __('metrics.lifetime_averages_video');
        $result = \DB::select($query);
        $result = $result[0];

        // Video likes per minute (lifetime)
        self::roundMetric('likes_perminute_video_lifetime', $result->likespm);

        // Video shares per minute (lifetime)
        self::roundMetric('shares_perminute_video_lifetime', $result->sharespm);

        // Video comments per minute (lifetime)
        self::roundMetric('comments_perminute_video_lifetime', $result->commentspm);

        // Video reactions per minute (lifetime)
        self::roundMetric('reactions_perminute_video_lifetime', $result->reactionspm);

        // Lifetime averages (links)
        $query = __('metrics.lifetime_averages_links');
        $result = \DB::select($query);
        $result = $result[0];

        // Link likes per minute (lifetime)
        self::roundMetric('likes_perminute_link_lifetime', $result->likespm);

        // Link shares per minute (lifetime)
        self::roundMetric('shares_perminute_link_lifetime', $result->sharespm);

        // Link comments per minute (lifetime)
        self::roundMetric('comments_perminute_link_lifetime', $result->commentspm);

        // Link reactions per minute (lifetime)
        self::roundMetric('reactions_perminute_link_lifetime', $result->reactionspm);

        // Lifetime averages (delayed)
        $query = __('metrics.lifetime_averages_delayed');
        $result = \DB::select($query);
        $result = $result[0];

        // Impressions per minute (lifetime)
        self::roundMetric('impressions_perminute_lifetime', $result->impressionspm);

        // Lifetime video averages (delayed)
        $query = __('metrics.lifetime_averages_video_delayed');
        $result = \DB::select($query);
        $result = $result[0];

        // Video mpressions per minute (lifetime)
        self::roundMetric('impressions_perminute_video_lifetime', $result->impressionspm);

        // Lifetime link averages (delayed)
        $query = __('metrics.lifetime_averages_links_delayed');
        $result = \DB::select($query);
        $result = $result[0];

        // Impressions per minute (lifetime)
        self::roundMetric('impressions_perminute_link_lifetime', $result->impressionspm);

    }

    /**
     * Round all Birth stats, and first 5 mins
     */
    public static function getBirthStatsAverages() : void
    {
        // Birth stats

        $query = __('metrics.birth_stats');
        $result = \DB::select($query);
        $result = $result[0];

        // Likes per minute (first 5 minutes)
        self::roundMetric('likes_perminute_birth', $result->likespm);

        // Shares per minute (first 5 minutes)
        self::roundMetric('shares_perminute_birth', $result->sharespm);

        // Comments per minute (first 5 minutes)
        self::roundMetric('comments_perminute_birth', $result->commentspm);

        // Reactions per minute (first 5 minutes)
        self::roundMetric('reactions_perminute_birth', $result->reactionspm);

        // Birth stats (video)
        $query = __('metrics.birth_stats_video');
        $result = \DB::select($query);
        $result = $result[0];

        // Video likes per minute (first 5 minutes)
        self::roundMetric('likes_perminute_video_birth', $result->likespm);

        // Video shares per minute (first 5 minutes)
        self::roundMetric('shares_perminute_video_birth', $result->sharespm);

        // Video comments per minute (first 5 minutes)
        self::roundMetric('comments_perminute_video_birth', $result->commentspm);

        // Video reactions per minute (first 5 minutes)
        self::roundMetric('reactions_perminute_video_birth', $result->reactionspm);

        // Birth stats (links)
        $query = __('metrics.birth_stats_links');
        $result = \DB::select($query);
        $result = $result[0];

        // Link likes per minute (first 5 minutes)
        self::roundMetric('likes_perminute_link_birth', $result->likespm);

        // Link shares per minute (first 5 minutes)
        self::roundMetric('shares_perminute_link_birth', $result->sharespm);

        // Link comments per minute (first 5 minutes)
        self::roundMetric('comments_perminute_link_birth', $result->commentspm);

        // Link reactions per minute (first 5 minutes)
        self::roundMetric('reactions_perminute_link_birth', $result->reactionspm);
    }

    /**
     * Round overall averages
     */
    public static function getOverallAverages(): void
    {
        // Average likes
        $query = __('metrics.average_likes');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('likes', $result->avglikes);

        // Average shares
        $query = __('metrics.average_shares');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('shares', $result->avgshares);

        // Average comments
        $query = __('metrics.average_comments');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('comments', $result->avgcomments);

        // Average reach
        $query = __('metrics.average_impressions');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('reach', $result->avgimpressions);

        // Average link clicks
        $query = __('metrics.average_clicks');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('link_clicks', $result->avgclicks);

        // Average likes (video)
        $query = __('metrics.average_likes_video');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('likes_video', $result->avglikes);

        // Average comments (video)
        $query = __('metrics.average_comment_video');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('comments_video', $result->avgcomments);

        // Average shares (video)
        $query = __('metrics.average_shares_video');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('shares_video', $result->avgshares);

        // Average reach (video)
        $query = __('metrics.average_reach_video');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('reach_video', $result->avgreach);

        // Average likes (link)
        $query = __('metrics.average_likes_link');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('likes_link', $result->avglikes);

        // Average comments (link)
        $query = __('metrics.average_comment_link');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('comments_link', $result->avgcomments);

        // Average shares (link)
        $query = __('metrics.average_shares_link');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('shares_link', $result->avgshares);

        // Average reach (link)
        $query = __('metrics.average_reach_link');
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('reach_link', $result->avgreach);
    }

    /**
     * Round daily averages
     */
    public static function getDailyAverages(): void
    {
        // Average daily reactions/shares/comments (all)
        $query = __('metrics.average_daily_reactions');
        $result = \DB::select($query);
        $result = $result[0];

        self::roundMetric('daily_reactions', $result->reactions);

        self::roundMetric('daily_shares', $result->shares);

        self::roundMetric('daily_comments', $result->comments);

        self::roundMetric('daily_reach', $result->reach);

        self::roundMetric('daily_likes', $result->likes);

        // Average daily reactions/shares/comments (video)
        $query = __('metrics.average_daily_reactions_video');
        $result = \DB::select($query);
        $result = $result[0];

        self::roundMetric('daily_reactions_video', $result->reactions);

        self::roundMetric('daily_shares_video', $result->shares);

        self::roundMetric('daily_comments_video', $result->comments);

        self::roundMetric('daily_reactions_article', 161000);

        self::roundMetric('daily_shares_article', 25800);

        self::roundMetric('daily_comments_article', 64500);

        // Daily reach (videos)
        $query = __('metrics.average_daily_reach_video');
        $result = \DB::select($query);
        $result = $result[0];

        self::roundMetric('daily_reach_video', $result->impressions);

        self::roundMetric('daily_reach_article', 15200000);

        self::roundMetric('daily_link_clicks', 2250000);
    }
}
