<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['facebook_id'];

    public $latestSnapshot;

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
            $this->latestSnapshot = \App\PostStatSnapshot::where('post_id', $this->id)->orderBy('id', 'DESC')->take(1)->first();
            return $this->latestSnapshot;
        }
    }
 

}
