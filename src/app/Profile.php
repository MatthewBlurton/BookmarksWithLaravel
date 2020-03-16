<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    // All fields except the id's are available for mass assignment
    protected $guarded = ['id, user_id'];

    // The id of the profile is hidden because the user_id is more important
    protected $hidden = ['id'];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}
