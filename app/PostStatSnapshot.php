<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostStatSnapshot extends Model
{
    /**
     * Default attributes
     */
    protected $attributes = ['likes' => 0, 
        'loves' => 0, 
        'wows' => 0, 
        'hahas' => 0, 
        'sads' => 0, 
        'angrys' => 0, 
        'shares' => 0, 
        'comments' => 0];

    /**
     * Return post model for this snapshot
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
