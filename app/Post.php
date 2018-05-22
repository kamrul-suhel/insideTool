<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AverageMetric;
use Illuminate\Support\Collection;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['facebook_id'];

    public $latestSnapshot;

    public $latestDelayedSnapshot;

    public $birthSnapshot;

    public $birthDelayedSnapshot;

    public $exportHeadings = [
        'facebook_id','published by', 'link', 'post message',
        'posted', 'deleted', 'reach', 'reactions', 'shares', 'like', 'comments', 'link clicks',
        'engagement', 'type', 'GA:avg page time', 'GA:page views', 'GA:avg load time (sec)',
        'GA:bounce rate', '% of Engagement (eng/total eng)'
    ];

    public $exportTotalHeadings = [
        '','','','', 'Articles', 'Videos', '','','','','','',
    ];

    /**
     * Return pages associated with this post
     */
    public function page()
    {
        return $this->belongsTo('App\Page');
    }

    /**
     * Return live stat snapshots
     */
    public function statSnapshots()
    {
        return $this->hasMany('App\PostStatSnapshot');
    }

    /**
     * Return delayed stat snapshots
     */
    public function delayedStatSnapshots()
    {
        return $this->hasMany('App\PostDelayedStatSnapshot');
    }

    /**
     * Return video labels
     */
    public function videoLabels()
    {
        return $this->belongsToMany('App\VideoLabel');
    }

    /**
     * Post creator
     */
    public function creator()
    {
        return $this->belongsTo('App\Creator');
    }

    /**
     * Static method to set hidden fields when serialising
     */
    public static function setHiddenFields($fields = []) {
       static::$hiddenFields = $fields;
    }

    /**
     * Link clicks attribute
     */
    public function getLinkClicksAttribute()
    {
        return $this->latestDelayedStatSnapshot()->link_clicks;
    }

    /**
     * Set hidden fields when serialising
     */
    public function toJson($options = 0) {
        $this->setHidden(static::$hiddenFields);
        return parent::toJson($options);
    }

    /**
     * @return mixed
     * Get all posts included deletions, eager load page, and creator
     */
    public function getAllPosts()
    {
        return $this->withTrashed()->orderBy('posted', 'desc')->with(['page', 'creator']);
    }

    /**
     * @param $model
     * @param $entity
     * @return mixed
     * @override
     */
    public function whereHasEntity($model, $entity)
    {
        return $this->whereHas($model, function ($q) use ($entity) {
            $q->where('id', (int) $entity);
        });
    }

    /**
     * Get latest stat snapshot
     */
    public function latestStatSnapshot()
    {
        if (!is_null($this->latestSnapshot)) {
            return $this->latestSnapshot;
        } else {
            $this->latestSnapshot = \App\PostStatSnapshot::where('post_id', $this->id)
                ->where('likes', '>', 0)
                ->orderBy('id', 'DESC')
                ->take(1)
                ->firstOrNew([]);
            return $this->latestSnapshot;
        }
    }

    /**
     * Get latest delayed stat snapshot
     */
    public function latestDelayedStatSnapshot()
    {
        if (!is_null($this->latestDelayedSnapshot)) {
            return $this->latestDelayedSnapshot;
        } else {
            $this->latestDelayedSnapshot = \App\PostDelayedStatSnapshot::where('post_id', $this->id)
                ->where('impressions', '>', 0)
                ->orderBy('id', 'DESC')
                ->take(1)
                ->firstOrNew([]);
            return $this->latestDelayedSnapshot;
        }
    }

    /**
     * Get the last available stat snapshot from the first 5 minutes of the post
     */
    public function birthStatSnapshot()
    {
        if (!is_null($this->birthSnapshot)) {
            return $this->birthSnapshot;
        } else {
            $this->birthSnapshot = \App\PostStatSnapshot::where('post_id', $this->id)->where('likes', '>', 0)
                ->where('created_at', '<', \Carbon\Carbon::parse($this->posted)->addMinutes(5))
                ->orderBy('id', 'DESC')
                ->firstOrNew([]);
            return $this->birthSnapshot;
        }
    }

    /**
     * Is a post's metric under the average?
     */
    public function isUnderAverage($metric, $timeAdjusted = true, $type = false) 
    {
        return ($this->getTarget($metric, $timeAdjusted, $type) > $this->$metric) ? true : false;
    }

    /**
     * Returns the target, time adjusted
     */
    public function getTarget($metric, $timeAdjusted = true, $type = false) {
        if ($type) {
            $metric = $metric . "_{$type}";
        }

        $averageMetric = AverageMetric::where(['key' => $metric])->first();
        
        if ($averageMetric) {
            $average = $averageMetric->average;
            if ($timeAdjusted) {
                $postAge = \Carbon\Carbon::parse($this->posted)->diffInMinutes();
                if ($postAge >= 2880) {
                    if (round($average) > 0) {
                        return round($average);
                    }
                    return 1;
                } else {
                    $timePercent = ($postAge / 2880) * 100;
                    $adjustedAverage = ($timePercent / 100) * $average;
                    if ($adjustedAverage > 0) {
                        return round($adjustedAverage);
                    }
                    return 1;
                }
            } else {
                if (round($average) > 0) {
                    return round($average);
                }
                return 1;
            }
        } else {
            return 1;
        }
    }

    /**
     * Gets the percentage from/above the target
     * @param $metric
     * @param bool $timeAdjusted
     * @param bool $type
     * @return float|int
     */
    public function percentageFromTarget($metric, $timeAdjusted = true, $type = false)
    {
        if ($this->$metric > 0) {
            $target = $this->getTarget($metric, $timeAdjusted, $type);

            if($target == 0.0)
                $target = 0.1;

            return abs(100 - ($this->$metric / $target) * 100);
        }
        return 100;
    }

    /**
     * Calculate the total of all posts provided with a specific column
     * @param string $value
     * @param Collection $model
     * @return int
     */
    public function calculateTotal(string $value, Collection $model): int
    {
        return array_sum($model->pluck($value)->toArray());
    }

    /**
     * Calculate engagement level
     * @param Collection $posts
     * @return int
     */
    public function calculateEngagement(Collection $posts): int
    {
        $totalShares = $posts->pluck('shares')->toArray();
        $totalLikes = $posts->pluck('likes')->toArray();
        $totalComments = $posts->pluck('comments')->toArray();

        return array_sum($totalShares) + array_sum($totalLikes) + array_sum($totalComments);
    }
}
