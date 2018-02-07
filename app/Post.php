<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Return pages associated with this post
     */
    public function pages()
    {
        return $this->belongsToMany('App\Page');
    }

    /**
     * Return pages associated with this post
     */
    public function statSnapshots()
    {
        return $this->hasMany('App\PostStatSnapshot');
    }
}
