<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
    public function announcements()
    {
        return $this->hasMany('App\Announcement');
    }
}
