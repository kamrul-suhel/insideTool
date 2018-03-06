<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoStatSnapshot extends Model
{

    protected $guarded = ['id', 'post_id', 'created_at', 'updated_at'];

    /**
     * Return post model for this snapshot
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
