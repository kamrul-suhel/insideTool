<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublishedInstantArticle extends Model
{
    protected $fillable = ['facebook_id', 'canonical_url'];
}
