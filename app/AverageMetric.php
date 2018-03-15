<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AverageMetric extends Model
{
    public $fillable = ['key'];

    public static function updateAverages()
    {
        // Lifetime averages
        $query = "SELECT AVG(posts.likespm) as likespm, AVG(posts.sharespm) as sharespm, AVG(posts.commentspm) AS commentspm
        FROM
         (SELECT MAX(likes) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as likespm,
         MAX(shares) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as sharespm,
         MAX(comments) / TIMESTAMPDIFF(MINUTE, MIN(post_stat_snapshots.created_at), NOW()) as commentspm
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
        
        $birthQuery = "SELECT AVG(likespm) AS likespm, AVG(sharespm) AS sharespm, AVG(commentspm) AS commentspm FROM (
            SELECT MAX(likes) / 5 as likespm, MAX(shares) / 5 as sharespm, MAX(comments) / 5 as commentspm 
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

        // Average likes
        $query = "SELECT AVG(maxlikes) as avglikes FROM (SELECT MAX(likes) as maxlikes FROM post_stat_snapshots GROUP BY post_id) posts";
        $metric = $metric->firstOrNew(['key' => 'likes']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avglikes);
        $metric->save();

        // Average shares
        $query = "SELECT AVG(maxshares) as avgshares FROM (SELECT MAX(shares) as maxshares FROM post_stat_snapshots GROUP BY post_id) posts";
        $metric = $metric->firstOrNew(['key' => 'shares']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgshares);
        $metric->save();

        // Average shares
        $query = "SELECT AVG(maxcomments) as avgcomments FROM (SELECT MAX(comments) as maxcomments FROM post_stat_snapshots GROUP BY post_id) posts";
        $metric = $metric->firstOrNew(['key' => 'comments']);
        $result = \DB::select($query);
        $metric->average = round($result[0]->avgcomments);
        $metric->save();

    }
}
