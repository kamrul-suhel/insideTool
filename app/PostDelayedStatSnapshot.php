<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostDelayedStatSnapshot extends Model
{
    /**
     * Return post model for this snapshot
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
