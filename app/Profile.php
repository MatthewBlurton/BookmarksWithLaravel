<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // All fields accessible for mass assignment
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
