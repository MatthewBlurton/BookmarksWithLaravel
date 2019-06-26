<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $fillable = [
        'title', 'url', 'description'
    ];

    // protected $guarded = [];
    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

}
