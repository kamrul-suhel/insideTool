<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['facebook_id', 'comment', 'comment_id', 'created_at'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'facebook_id', 'facebook_id');
    }

}

