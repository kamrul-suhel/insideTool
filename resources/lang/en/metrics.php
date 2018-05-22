<?php

return [

    'lifetime_averages' => "SELECT AVG(posts.likespm) as likespm, AVG(posts.sharespm) as sharespm, AVG(posts.commentspm) AS commentspm, AVG(posts.reactionspm) AS reactionspm
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
        posts",

    'lifetime_averages_video' => "SELECT AVG(posts.likespm) as likespm, AVG(posts.sharespm) as sharespm, AVG(posts.commentspm) AS commentspm, AVG(posts.reactionspm) AS reactionspm
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
        posts",

    'lifetime_averages_links' => "SELECT AVG(posts.likespm) as likespm, AVG(posts.sharespm) as sharespm, AVG(posts.commentspm) AS commentspm, AVG(posts.reactionspm) AS reactionspm
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
        posts",

    'lifetime_averages_delayed' => "SELECT AVG(posts.impressionspm) as impressionspm
        FROM
        (SELECT MAX(post_delayed_stat_snapshots.impressions) / TIMESTAMPDIFF(MINUTE, MIN(post_delayed_stat_snapshots.created_at), NOW()) as impressionspm
        FROM post_delayed_stat_snapshots, posts 
        WHERE posts.id = post_delayed_stat_snapshots.post_id 
        AND post_delayed_stat_snapshots.impressions > 0
        AND posts.deleted_at IS NULL 
        AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
        GROUP BY post_id) 
        posts",

    'lifetime_averages_video_delayed' => "SELECT AVG(posts.impressionspm) as impressionspm
        FROM
        (SELECT MAX(post_delayed_stat_snapshots.impressions) / TIMESTAMPDIFF(MINUTE, MIN(post_delayed_stat_snapshots.created_at), NOW()) as impressionspm
        FROM post_delayed_stat_snapshots, posts 
        WHERE posts.id = post_delayed_stat_snapshots.post_id 
        AND post_delayed_stat_snapshots.impressions > 0
        AND posts.deleted_at IS NULL
        AND posts.type = 'video'
        AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
        GROUP BY post_id) 
        posts",

    'lifetime_averages_links_delayed' => "SELECT AVG(posts.impressionspm) as impressionspm
        FROM
        (SELECT MAX(post_delayed_stat_snapshots.impressions) / TIMESTAMPDIFF(MINUTE, MIN(post_delayed_stat_snapshots.created_at), NOW()) as impressionspm
        FROM post_delayed_stat_snapshots, posts 
        WHERE posts.id = post_delayed_stat_snapshots.post_id 
        AND post_delayed_stat_snapshots.impressions > 0
        AND posts.deleted_at IS NULL
        AND posts.type = 'link'
        AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
        GROUP BY post_id) 
        posts",

    'birth_stats' => "SELECT AVG(likespm) AS likespm, AVG(sharespm) AS sharespm, AVG(commentspm) AS commentspm, AVG(reactionspm) AS reactionspm FROM (
        SELECT MAX(post_stat_snapshots.likes) / 5 as likespm, MAX(post_stat_snapshots.shares) / 5 as sharespm, MAX(post_stat_snapshots.comments) / 5 as commentspm,
        (MAX(post_stat_snapshots.likes) + MAX(post_stat_snapshots.loves) + MAX(post_stat_snapshots.wows) + MAX(post_stat_snapshots.hahas) + MAX(post_stat_snapshots.sads)
        + MAX(post_stat_snapshots.angrys)) / 5 as reactionspm
        FROM post_stat_snapshots, posts 
        WHERE posts.id = post_stat_snapshots.post_id
        AND posts.deleted_at IS NULL
        AND posted > DATE_SUB(NOW(), INTERVAL 48 HOUR) 
        AND post_stat_snapshots.created_at < DATE_ADD(posts.posted, INTERVAL 5 MINUTE)
        GROUP BY post_id
        ) posts",

    'birth_stats_video' => "SELECT AVG(likespm) AS likespm, AVG(sharespm) AS sharespm, AVG(commentspm) AS commentspm, AVG(reactionspm) AS reactionspm FROM (
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
        ) posts",

    'birth_stats_links' => "SELECT AVG(likespm) AS likespm, AVG(sharespm) AS sharespm, AVG(commentspm) AS commentspm, AVG(reactionspm) AS reactionspm FROM (
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
        ) posts",

    'average_likes' => "SELECT AVG(maxlikes) as avglikes FROM (SELECT MAX(post_stat_snapshots.likes) as maxlikes FROM post_stat_snapshots GROUP BY post_id) posts",

    'average_shares' => "SELECT AVG(maxshares) as avgshares FROM (SELECT MAX(post_stat_snapshots.shares) as maxshares FROM post_stat_snapshots GROUP BY post_id) posts",

    'average_comments' => "SELECT AVG(maxcomments) as avgcomments FROM (SELECT MAX(post_stat_snapshots.comments) as maxcomments FROM post_stat_snapshots GROUP BY post_id) posts",

    'average_impressions' => "SELECT AVG(maximpressions) as avgimpressions FROM (SELECT MAX(post_delayed_stat_snapshots.impressions) as maximpressions FROM post_delayed_stat_snapshots WHERE post_delayed_stat_snapshots.impressions > 0 GROUP BY post_id) posts",

    'average_clicks' => "SELECT AVG(maxclicks) as avgclicks FROM (SELECT MAX(post_delayed_stat_snapshots.link_clicks) as maxclicks FROM post_delayed_stat_snapshots LEFT JOIN posts ON posts.id = post_id WHERE posts.type = 'link' AND post_delayed_stat_snapshots.link_clicks > 0 GROUP BY post_id) posts",

    'average_likes_video' => "SELECT AVG(likes) as avglikes FROM posts WHERE type='video'",

    'average_comment_video' => "SELECT AVG(comments) as avgcomments FROM posts WHERE type='video'",

    'average_shares_video' => "SELECT AVG(shares) as avgshares FROM posts WHERE type='video'",

    'average_reach_video' => "SELECT AVG(reach) as avgreach FROM posts WHERE type='video'",

    'average_likes_link' => "SELECT AVG(likes) as avglikes FROM posts WHERE type='link'",

    'average_comment_link' => "SELECT AVG(comments) as avgcomments FROM posts WHERE type='link'",

    'average_shares_link' => "SELECT AVG(shares) as avgshares FROM posts WHERE type='link'",

    'average_reach_link' => "SELECT AVG(reach) as avgreach FROM posts WHERE type='link'",

    'average_daily_reactions' => "SELECT
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
            dateposted) daily",

    'average_daily_reactions_video' => "SELECT
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
            dateposted) daily",

    'average_daily_reach_video' => "SELECT
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
            dateposted) daily",
];
