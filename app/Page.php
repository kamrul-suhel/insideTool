<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * Return posts associated with this page
     */
    public function posts()
    {
        return $this->belongsToMany('App\Post');
    }
}
