<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    public function user()
    {
        $this->belongsTo('App\User');
    }
    public function course()
    {
        $this->belongsTo('App\Course');
    } 
}
