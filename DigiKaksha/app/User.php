<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','user_level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->belongsToMany('App\Course');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
    public function announcements()
    {
        return $this->hasMany('App\Announcement');
    }
    public function submissions()
    {
        return $this->hasMany('App\Submission');
    }
    public function hasSubmission($announcement){
        foreach($this->submissions as $submission) if($submission->announcement_id == $announcement->id) return true;
        return false;
    }
    public function getScore($course){
        $i=0;
        foreach($this->submissions as $submission) if($submission->announcement->course_id==$course->id) $i+=$submission->grade;
        return $i;
    }
    public function getMaxScore($course){
        $i=0;
        foreach($this->submissions as $submission) if($submission->announcement->course_id==$course->id) $i+=$submission->announcement->max_grade;
        return $i;
    }
    public function evaluationNo($course){
        $i=0;
        foreach($this->submissions as $submission) if($submission->announcement->course_id==$course->id) if($submission->grade!=NULL)$i+=1;
        return $i;
    }
}
