<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //Groups of Students
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
