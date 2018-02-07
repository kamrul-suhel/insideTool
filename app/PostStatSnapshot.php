<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostStatSnapshot extends Model
{
    /**
     * Return post model for this snapshot
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
