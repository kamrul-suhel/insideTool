<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['facebook_id'];

    public $latestSnapshot;

    public $latestDelayedSnapshot;

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
     * Set hidden fields when serialising
     */
    public function toJson($options = 0) {
        $this->setHidden(static::$hiddenFields);
        return parent::toJson($options);
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

}
