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
     *
     */
    public static function getLifetimeAverages()
    {
        // Lifetime averages
        $query = "SELECT AVG(posts.likespm) as likespm, AVG(posts.sharespm) as sharespm, AVG(posts.commentspm) AS commentspm, AVG(posts.reactionspm) AS reactionspm
        FROM
         (SELECT MAX(post_stat_snapshots.likes) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as likespm,
         MAX(post_stat_snapshots.shares) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as sharespm,
         MAX(post_stat_snapshots.comments) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as commentspm,
         (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads)
                + MAX(post_stat_snapshots.angrys)) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as reactionspm
           FROM post_stat_snapshots, posts 
           WHERE posts.id = post_stat_snapshots.post_id 
           AND posts.deleted_at IS NULL 
           AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
           GROUP BY post_id) 
         posts";
        $result = \DB::select($query);
        $result = $result[0];

        // Likes per minute (lifetime)
        self::roundMetric('likes_perminute_lifetime', $result->likespm);

        // Shares per minute (lifetime)
        self::roundMetric('shares_perminute_lifetime', $result->sharesp);

        // Comments per minute (lifetime)
        self::roundMetric('comments_perminute_lifetime', $result->commentspm);

        // Reactions per minute (lifetime)
        self::roundMetric('reactions_perminute_lifetime', $result->reactionspm);

        // Lifetime averages (video)
        $query = "SELECT AVG(posts.likespm) as likespm, AVG(posts.sharespm) as sharespm, AVG(posts.commentspm) AS commentspm, AVG(posts.reactionspm) AS reactionspm
        FROM
            (SELECT MAX(post_stat_snapshots.likes) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as likespm,
            MAX(post_stat_snapshots.shares) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as sharespm,
            MAX(post_stat_snapshots.comments) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as commentspm,
            (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads)
                + MAX(post_stat_snapshots.angrys)) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as reactionspm
            FROM post_stat_snapshots, posts 
            WHERE posts.id = post_stat_snapshots.post_id 
            AND posts.deleted_at IS NULL 
            AND posts.type = 'video'
            AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
            GROUP BY post_id) 
            posts";
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
        $query = "SELECT AVG(posts.likespm) as likespm, AVG(posts.sharespm) as sharespm, AVG(posts.commentspm) AS commentspm, AVG(posts.reactionspm) AS reactionspm
        FROM
            (SELECT MAX(post_stat_snapshots.likes) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as likespm,
            MAX(post_stat_snapshots.shares) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as sharespm,
            MAX(post_stat_snapshots.comments) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as commentspm,
            (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads)
                + MAX(post_stat_snapshots.angrys)) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as reactionspm
            FROM post_stat_snapshots, posts 
            WHERE posts.id = post_stat_snapshots.post_id 
            AND posts.deleted_at IS NULL 
            AND posts.type = 'link'
            AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
            GROUP BY post_id) 
            posts";
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
        $query = "SELECT AVG(posts.impressionspm) as impressionspm
        FROM
            (SELECT MAX(post_delayed_stat_snapshots.impressions) / TIMESTAMPDIFF(MINUTE, MIN(post_delayed_stat_snapshots.created_at), NOW()) as impressionspm
            FROM post_delayed_stat_snapshots, posts 
            WHERE posts.id = post_delayed_stat_snapshots.post_id 
            AND post_delayed_stat_snapshots.impressions > 0
            AND posts.deleted_at IS NULL 
            AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
            GROUP BY post_id) 
            posts";
        $result = \DB::select($query);
        $result = $result[0];

        // Impressions per minute (lifetime)
        self::roundMetric('impressions_perminute_lifetime', $result->impressionspm);

        // Lifetime video averages (delayed)
        $query = "SELECT AVG(posts.impressionspm) as impressionspm
        FROM
            (SELECT MAX(post_delayed_stat_snapshots.impressions) / TIMESTAMPDIFF(MINUTE, MIN(post_delayed_stat_snapshots.created_at), NOW()) as impressionspm
            FROM post_delayed_stat_snapshots, posts 
            WHERE posts.id = post_delayed_stat_snapshots.post_id 
            AND post_delayed_stat_snapshots.impressions > 0
            AND posts.deleted_at IS NULL
            AND posts.type = 'video'
            AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
            GROUP BY post_id) 
            posts";
        $result = \DB::select($query);
        $result = $result[0];

        // Video mpressions per minute (lifetime)
        self::roundMetric('impressions_perminute_video_lifetime', $result->impressionspm);

        // Lifetime link averages (delayed)
        $query = "SELECT AVG(posts.impressionspm) as impressionspm
        FROM
            (SELECT MAX(post_delayed_stat_snapshots.impressions) / TIMESTAMPDIFF(MINUTE, MIN(post_delayed_stat_snapshots.created_at), NOW()) as impressionspm
            FROM post_delayed_stat_snapshots, posts 
            WHERE posts.id = post_delayed_stat_snapshots.post_id 
            AND post_delayed_stat_snapshots.impressions > 0
            AND posts.deleted_at IS NULL
            AND posts.type = 'link'
            AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
            GROUP BY post_id) 
            posts";
        $result = \DB::select($query);
        $result = $result[0];

        // Impressions per minute (lifetime)
        self::roundMetric('impressions_perminute_link_lifetime', $result->impressionspm);

    }

    /**
     *
     */
    public static function getBirthStatsAverages()
    {
        // Birth stats

        $query = "SELECT AVG(likespm) AS likespm, AVG(sharespm) AS sharespm, AVG(commentspm) AS commentspm, AVG(reactionspm) AS reactionspm FROM (
            SELECT MAX(post_stat_snapshots.likes) / 5 as likespm, MAX(post_stat_snapshots.shares) / 5 as sharespm, MAX(post_stat_snapshots.comments) / 5 as commentspm,
                (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads)
                    + MAX(post_stat_snapshots.angrys)) / 5 as reactionspm
                FROM post_stat_snapshots, posts 
                WHERE posts.id = post_stat_snapshots.post_id
                AND posts.deleted_at IS NULL
                AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
                AND post_stat_snapshots.created_at < DATE_ADD(posts.posted, INTERVAL 5 MINUTE)
                GROUP BY post_id
            ) posts";
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
        $query = "SELECT AVG(likespm) AS likespm, AVG(sharespm) AS sharespm, AVG(commentspm) AS commentspm, AVG(reactionspm) AS reactionspm FROM (
            SELECT MAX(post_stat_snapshots.likes) / 5 as likespm, MAX(post_stat_snapshots.shares) / 5 as sharespm, MAX(post_stat_snapshots.comments) / 5 as commentspm,
                (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads)
                    + MAX(post_stat_snapshots.angrys)) / 5 as reactionspm
                FROM post_stat_snapshots, posts 
                WHERE posts.id = post_stat_snapshots.post_id
                AND posts.deleted_at IS NULL
                AND posts.type = 'video'
                AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
                AND post_stat_snapshots.created_at < DATE_ADD(posts.posted, INTERVAL 5 MINUTE)
                GROUP BY post_id
            ) posts";
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
        $query = "SELECT AVG(likespm) AS likespm, AVG(sharespm) AS sharespm, AVG(commentspm) AS commentspm, AVG(reactionspm) AS reactionspm FROM (
            SELECT MAX(post_stat_snapshots.likes) / 5 as likespm, MAX(post_stat_snapshots.shares) / 5 as sharespm, MAX(post_stat_snapshots.comments) / 5 as commentspm,
                (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads)
                    + MAX(post_stat_snapshots.angrys)) / 5 as reactionspm
                FROM post_stat_snapshots, posts 
                WHERE posts.id = post_stat_snapshots.post_id
                AND posts.deleted_at IS NULL
                AND posts.type = 'link'
                AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
                AND post_stat_snapshots.created_at < DATE_ADD(posts.posted, INTERVAL 5 MINUTE)
                GROUP BY post_id
            ) posts";
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
     *
     */
    public static function getDailyAverages()
    {
        // Average likes
        $query = "SELECT AVG(maxlikes) as avglikes FROM (SELECT MAX(post_stat_snapshots.likes) as maxlikes FROM post_stat_snapshots GROUP BY post_id) posts";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('likes', $result->avglikes);

        // Average shares
        $query = "SELECT AVG(maxshares) as avgshares FROM (SELECT MAX(post_stat_snapshots.shares) as maxshares FROM post_stat_snapshots GROUP BY post_id) posts";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('shares', $result->avgshares);

        // Average comments
        $query = "SELECT AVG(maxcomments) as avgcomments FROM (SELECT MAX(post_stat_snapshots.comments) as maxcomments FROM post_stat_snapshots GROUP BY post_id) posts";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('comments', $result->avgcomments);

        // Average reach
        $query = "SELECT AVG(maximpressions) as avgimpressions FROM (SELECT MAX(post_delayed_stat_snapshots.impressions) as maximpressions FROM post_delayed_stat_snapshots WHERE post_delayed_stat_snapshots.impressions > 0 GROUP BY post_id) posts";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('reach', $result->avgimpressions);

        // Average link clicks
        $query = "SELECT AVG(maxclicks) as avgclicks FROM (SELECT MAX(post_delayed_stat_snapshots.link_clicks) as maxclicks FROM post_delayed_stat_snapshots LEFT JOIN posts ON posts.id = post_id WHERE posts.type = 'link' AND post_delayed_stat_snapshots.link_clicks > 0 GROUP BY post_id) posts";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('link_clicks', $result->avgclicks);

        // Average likes (video)
        $query = "SELECT AVG(likes) as avglikes FROM posts WHERE type='video'";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('likes_video', $result->avglikes);

        // Average comments (video)
        $query = "SELECT AVG(comments) as avgcomments FROM posts WHERE type='video'";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('comments_video', $result->avgcomments);

        // Average shares (video)
        $query = "SELECT AVG(shares) as avgshares FROM posts WHERE type='video'";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('shares_video', $result->avgshares);

        // Average reach (video)
        $query = "SELECT AVG(reach) as avgreach FROM posts WHERE type='video'";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('reach_video', $result->avgreach);

        // Average likes (link)
        $query = "SELECT AVG(likes) as avglikes FROM posts WHERE type='link'";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('likes_link', $result->avglikes);

        // Average comments (link)
        $query = "SELECT AVG(comments) as avgcomments FROM posts WHERE type='link'";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('comments_link', $result->avgcomments);

        // Average shares (link)
        $query = "SELECT AVG(shares) as avgshares FROM posts WHERE type='link'";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('shares_link', $result->avgshares);

        // Average reach (link)
        $query = "SELECT AVG(reach) as avgreach FROM posts WHERE type='link'";
        $result = \DB::select($query);
        $result = $result[0];
        self::roundMetric('reach_link', $result->avgreach);

        // Average daily reactions/shares/comments (all)
        $query = "SELECT
            ROUND(AVG(dailyreactions)) AS reactions,
            ROUND(AVG(dailyshares)) AS shares,
            ROUND(AVG(dailycomments)) AS comments,
            ROUND(AVG(dailyreach)) AS reach,
            ROUND(AVG(dailylikes)) AS likes
        FROM (
            SELECT
                SUM(maxreactions) AS dailyreactions,
                SUM(maxshares) AS dailyshares,
                SUM(maxcomments) AS dailycomments,
                SUM(maxreach) AS dailyreach,
                SUM(maxlikes) AS dailylikes
            FROM (
                SELECT
                    post_id, (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads) + MAX(post_stat_snapshots.angrys)) AS maxreactions,
                    MAX(post_stat_snapshots.shares) AS maxshares,
                    MAX(post_stat_snapshots.comments) AS maxcomments,
                    MAX(posts.reach) AS maxreach,
                    MAX(posts.likes) AS maxlikes,
                    DATE(posted) AS dateposted
                FROM
                    post_stat_snapshots
                LEFT JOIN posts ON posts.id = post_id
            WHERE
                post_stat_snapshots.created_at < DATE_ADD(posted, INTERVAL 24 hour)
            GROUP BY
                post_id) posts
        GROUP BY
            dateposted) daily";
        $result = \DB::select($query);
        $result = $result[0];

        self::roundMetric('daily_reactions', $result->reactions);

        self::roundMetric('daily_shares', $result->shares);

        self::roundMetric('daily_comments', $result->comments);

        self::roundMetric('daily_reach', $result->reach);

        self::roundMetric('daily_likes', $result->likes);

        // Average daily reactions/shares/comments (video)
        $query = "SELECT
                ROUND(AVG(dailyreactions)) AS reactions,
                ROUND(AVG(dailyshares)) AS shares,
                ROUND(AVG(dailycomments)) AS comments
            FROM (
                SELECT
                    SUM(maxreactions) AS dailyreactions,
                    SUM(maxshares) AS dailyshares,
                    SUM(maxcomments) AS dailycomments
                FROM (
                    SELECT
                        post_id, (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads) + MAX(post_stat_snapshots.angrys)) AS maxreactions,
                        MAX(post_stat_snapshots.shares) AS maxshares,
                        MAX(post_stat_snapshots.comments) AS maxcomments,
                        DATE(posted) AS dateposted
                    FROM
                        post_stat_snapshots
                    LEFT JOIN posts ON posts.id = post_id
                WHERE
                    post_stat_snapshots.created_at < DATE_ADD(posted, INTERVAL 24 hour)
                AND
                    posts.type = 'video'
                GROUP BY
                    post_id) posts
            GROUP BY
                dateposted) daily";
        $result = \DB::select($query);
        $result = $result[0];

        self::roundMetric('daily_reactions_video', $result->reactions);

        self::roundMetric('daily_shares_video', $result->shares);

        self::roundMetric('daily_comments_video', $result->comments);

        self::roundMetric('daily_reactions_article', 161000);

        self::roundMetric('daily_shares_article', 25800);

        self::roundMetric('daily_comments_article', 64500);

        // Daily reach (videos)
        $query = "SELECT
            ROUND(AVG(dailyimpressions)) AS impressions
        FROM (
            SELECT
                SUM(maximpressions) AS dailyimpressions
            FROM (
                SELECT
                    MAX(impressions) AS maximpressions,
                    DATE(posted) AS dateposted
                FROM
                    post_delayed_stat_snapshots
                LEFT JOIN posts ON posts.id = post_id
            WHERE
                post_delayed_stat_snapshots.created_at < DATE_ADD(posted, INTERVAL 24 hour)
                AND posts.type = 'video'
            GROUP BY
                post_id) posts
        GROUP BY
            dateposted) daily";
        $result = \DB::select($query);
        $result = $result[0];

        self::roundMetric('daily_reach_video', $result->impressions);

        self::roundMetric('daily_reach_article', 15200000);

        self::roundMetric('daily_link_clicks', 2250000);
    }

    /**
     *
     */
    public static function updateAverages()
    {
        self::getLifetimeAverages();
        self::getBirthStatsAverages();
        self::getDailyAverages();
    }
}
