<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // All fields accessible for mass assignment
    protected $guarded = [];

    protected $hidden = ['id', 'user_id', 'created_at', 'updated_at'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
