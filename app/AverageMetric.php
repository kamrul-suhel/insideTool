<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AverageMetric extends Model
{
    public $fillable = ['key'];

    public static function updateAverages()
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
         posts
        ";

        $result = \DB::select($query);

        // Likes per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'likes_perminute_lifetime']);
        $metric->average = round($result[0]->likespm);
        $metric->save();

        // Shares per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'shares_perminute_lifetime']);
        $metric->average = round($result[0]->sharespm);
        $metric->save();

        // Comments per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'comments_perminute_lifetime']);
        $metric->average = round($result[0]->commentspm);
        $metric->save();

        // Reactions per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'reactions_perminute_lifetime']);
        $metric->average = round($result[0]->reactionspm);
        $metric->save();

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
            posts
        ";

        $result = \DB::select($query);

        // Video likes per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'likes_perminute_video_lifetime']);
        $metric->average = round($result[0]->likespm);
        $metric->save();

        // Video shares per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'shares_perminute_video_lifetime']);
        $metric->average = round($result[0]->sharespm);
        $metric->save();

        // Video comments per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'comments_perminute_video_lifetime']);
        $metric->average = round($result[0]->commentspm);
        $metric->save();

        // Video reactions per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'reactions_perminute_video_lifetime']);
        $metric->average = round($result[0]->reactionspm);
        $metric->save();
        
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
            posts
        ";

        $result = \DB::select($query);

        // Link likes per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'likes_perminute_link_lifetime']);
        $metric->average = round($result[0]->likespm);
        $metric->save();

        // Link shares per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'shares_perminute_link_lifetime']);
        $metric->average = round($result[0]->sharespm);
        $metric->save();

        // Link comments per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'comments_perminute_link_lifetime']);
        $metric->average = round($result[0]->commentspm);
        $metric->save();

        // Link reactions per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'reactions_perminute_link_lifetime']);
        $metric->average = round($result[0]->reactionspm);
        $metric->save();


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
            posts
        ";

        $result = \DB::select($query);

        // Impressions per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'impressions_perminute_lifetime']);
        $metric->average = round($result[0]->impressionspm);
        $metric->save();

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
            posts
        ";

        $result = \DB::select($query);

        // Video mpressions per minute (lifetime)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'impressions_perminute_video_lifetime']);
        $metric->average = round($result[0]->impressionspm);
        $metric->save();        
        
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
            posts
        ";

        $result = \DB::select($query);

        // Birth stats
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'impressions_perminute_link_lifetime']);
        $metric->average = round($result[0]->impressionspm);
        $metric->save();                
        
        $birthQuery = "SELECT AVG(likespm) AS likespm, AVG(sharespm) AS sharespm, AVG(commentspm) AS commentspm, AVG(reactionspm) AS reactionspm FROM (
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

        $result = \DB::select($birthQuery);

        // Likes per minute (first 5 minutes)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'likes_perminute_birth']);
        $metric->average = round($result[0]->likespm);
        $metric->save();

        // Shares per minute (first 5 minutes)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'shares_perminute_birth']);
        $metric->average = round($result[0]->sharespm);
        $metric->save();

        // Comments per minute (first 5 minutes)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'comments_perminute_birth']);
        $metric->average = round($result[0]->commentspm);
        $metric->save();

        // Reactions per minute (first 5 minutes)
        $metric = new static();
        $metric = $metric->firstOrNew(['key' => 'reactions_perminute_birth']);
        $metric->average = round($result[0]->reactionspm);
        $metric->save();

        // Average likes
        $query = "SELECT AVG(maxlikes) as avglikes FROM (SELECT MAX(post_stat_snapshots.likes) as maxlikes FROM post_stat_snapshots GROUP BY post_id) posts";
        $metric = $metric->firstOrNew(['key' => 'likes']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avglikes);
        $metric->save();

        // Average shares
        $query = "SELECT AVG(maxshares) as avgshares FROM (SELECT MAX(post_stat_snapshots.shares) as maxshares FROM post_stat_snapshots GROUP BY post_id) posts";
        $metric = $metric->firstOrNew(['key' => 'shares']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgshares);
        $metric->save();

        // Average comments
        $query = "SELECT AVG(maxcomments) as avgcomments FROM (SELECT MAX(post_stat_snapshots.comments) as maxcomments FROM post_stat_snapshots GROUP BY post_id) posts";
        $metric = $metric->firstOrNew(['key' => 'comments']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgcomments);
        $metric->save();

        // Average reach
        $query = "SELECT AVG(maximpressions) as avgimpressions FROM (SELECT MAX(post_delayed_stat_snapshots.impressions) as maximpressions FROM post_delayed_stat_snapshots WHERE post_delayed_stat_snapshots.impressions > 0 GROUP BY post_id) posts";
        $metric = $metric->firstOrNew(['key' => 'reach']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgimpressions);
        $metric->save();

        // Average link clicks
        $query = "SELECT AVG(maxclicks) as avgclicks FROM (SELECT MAX(post_delayed_stat_snapshots.link_clicks) as maxclicks FROM post_delayed_stat_snapshots LEFT JOIN posts ON posts.id = post_id WHERE posts.type = 'link' AND post_delayed_stat_snapshots.link_clicks > 0 GROUP BY post_id) posts";
        $metric = $metric->firstOrNew(['key' => 'link_clicks']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgclicks);
        $metric->save();

        // Average likes (video)
        $query = "SELECT AVG(likes) as avglikes FROM posts WHERE type='video'";
        $metric = $metric->firstOrNew(['key' => 'likes_video']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avglikes);
        $metric->save();

        // Average comments (video)
        $query = "SELECT AVG(comments) as avgcomments FROM posts WHERE type='video'";
        $metric = $metric->firstOrNew(['key' => 'comments_video']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgcomments);
        $metric->save();
        
        // Average shares (video)
        $query = "SELECT AVG(shares) as avgshares FROM posts WHERE type='video'";
        $metric = $metric->firstOrNew(['key' => 'shares_video']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgshares);
        $metric->save();

        // Average reach (video)
        $query = "SELECT AVG(reach) as avgreach FROM posts WHERE type='video'";
        $metric = $metric->firstOrNew(['key' => 'reach_video']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgreach);
        $metric->save();

        // Average likes (link)
        $query = "SELECT AVG(likes) as avglikes FROM posts WHERE type='link'";
        $metric = $metric->firstOrNew(['key' => 'likes_link']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avglikes);
        $metric->save();

        // Average comments (link)
        $query = "SELECT AVG(comments) as avgcomments FROM posts WHERE type='link'";
        $metric = $metric->firstOrNew(['key' => 'comments_link']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgcomments);
        $metric->save();
        
        // Average shares (link)
        $query = "SELECT AVG(shares) as avgshares FROM posts WHERE type='link'";
        $metric = $metric->firstOrNew(['key' => 'shares_link']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgshares);
        $metric->save();

        // Average reach (link)
        $query = "SELECT AVG(reach) as avgreach FROM posts WHERE type='link'";
        $metric = $metric->firstOrNew(['key' => 'reach_link']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgreach);
        $metric->save();

        // Average daily reactions/shares/comments (all)
        $query = "
        SELECT
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
            dateposted) daily
        ";
        $result = \DB::select($query);
        $metric = $metric->firstOrNew(['key' => 'daily_reactions']);
        $metric->average = $result[0]->reactions;
        $metric->save();

        $metric = $metric->firstOrNew(['key' => 'daily_shares']);
        $metric->average = $result[0]->shares;
        $metric->save();

        $metric = $metric->firstOrNew(['key' => 'daily_comments']);
        $metric->average = $result[0]->comments;
        $metric->save();

        $metric = $metric->firstOrNew(['key' => 'daily_reach']);
        $metric->average = $result[0]->reach;
        $metric->save();

        $metric = $metric->firstOrNew(['key' => 'daily_likes']);
        $metric->average = $result[0]->likes;
        $metric->save();
        
        // Average daily reactions/shares/comments (video)
        $query = "
            SELECT
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
                dateposted) daily
        ";
        $result = \DB::select($query);
        $metric = $metric->firstOrNew(['key' => 'daily_reactions_video']);
        $metric->average = $result[0]->reactions;
        $metric->save();

        $metric = $metric->firstOrNew(['key' => 'daily_shares_video']);
        $metric->average = $result[0]->shares;
        $metric->save();

        $metric = $metric->firstOrNew(['key' => 'daily_comments_video']);
        $metric->average = $result[0]->comments;
        $metric->save();

        // Average daily reactions/shares/comments (articles)
        $query = "
            SELECT
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
                        posts.type = 'link'
                    GROUP BY
                        post_id) posts
            GROUP BY
                dateposted) daily
        ";
        $result = \DB::select($query);
        $metric = $metric->firstOrNew(['key' => 'daily_reactions_article']);
        $metric->average = $result[0]->reactions;
        $metric->save();

        $metric = $metric->firstOrNew(['key' => 'daily_shares_article']);
        $metric->average = $result[0]->shares;
        $metric->save();

        $metric = $metric->firstOrNew(['key' => 'daily_comments_article']);
        $metric->average = $result[0]->comments;
        $metric->save();

        // Daily reach (videos)
        $query = "
        SELECT
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
            dateposted) daily
        ";
        $result = \DB::select($query);
        $metric = $metric->firstOrNew(['key' => 'daily_reach_video']);
        $metric->average = $result[0]->impressions;
        $metric->save();

        // Daily reach (articles)
        $query = "
        SELECT
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
                AND posts.type = 'link'
            GROUP BY
                post_id) posts
        GROUP BY
            dateposted) daily
        ";
        $result = \DB::select($query);
        $metric = $metric->firstOrNew(['key' => 'daily_reach_article']);
        $metric->average = $result[0]->impressions;
        $metric->save();

        // Daily link clicks
        $query = "
        SELECT
            ROUND(AVG(dailyclicks)) AS clicks
        FROM (
            SELECT
                SUM(maxclicks) AS dailyclicks
            FROM (
                SELECT
                    MAX(post_delayed_stat_snapshots.link_clicks) AS maxclicks,
                    DATE(posted) AS dateposted
                FROM
                    post_delayed_stat_snapshots
                LEFT JOIN posts ON posts.id = post_id
                WHERE
                    post_delayed_stat_snapshots.created_at < DATE_ADD(posted, INTERVAL 24 hour)
                    AND post_delayed_stat_snapshots.link_clicks > 0
                    AND posts.type = 'link'
                GROUP BY
                    post_id
            ) posts
        GROUP BY
            dateposted) daily
        ";
        $result = \DB::select($query);
        $metric = $metric->firstOrNew(['key' => 'daily_link_clicks']);
        $metric->average = $result[0]->clicks;
        $metric->save();
        
        
    }
}
