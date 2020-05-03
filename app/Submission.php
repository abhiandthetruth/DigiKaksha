<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public function announcement()
    {
        return $this->belongsTo('App\Announcement');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
