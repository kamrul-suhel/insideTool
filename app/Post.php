<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['facebook_id'];

    /**
     * Return pages associated with this post
     */
    public function pages()
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

}
