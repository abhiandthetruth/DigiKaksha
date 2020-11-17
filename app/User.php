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
    public function comments()
    {
        return $this->hasMany('App\Comment');
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
    public function getTotalCourses(){
        $i=0;
        if($this->user_level==1){
            foreach($this->groups as $group){
                $i+=count($group->courses);
            }
        }
        elseif($this->user_level==2) $i = count($this->courses);
        else $i = count(\App\Course::all());
        return $i;
    }
    public function averageMarks(){
        $i=0;
        $j=0;
        foreach($this->groups as $group){
            foreach($group->courses as $course){
                $max = $this->getMaxScore($course);
                if($max!=0){
                    $i+=$this->getScore($course)/$max;
                    $j++;
                }
            }
        }
        if($j!=0){
            $r = $i*100/$j;
            return $r;
        }
        else return 100;
    }
    public function getTotalClasses()
    {
        $i=0;
        if($this->user_level==1){
            $i = count($this->groups);
        }
        elseif($this->user_level==2){
            foreach($this->courses as $course){
                $i+=count($course->groups);
            }
        }
        else $i = count(\App\Group::all());
        return $i;
    }
    public function getTotalStudents()
    {
        $i=0;
        $users = \App\User::all();
        foreach($users as $user){
            if($user->user_level==1) $i++;
        }
        return $i;
    }
    public function getTotalInstructors()
    {
        $i=0;
        $users = \App\User::all();
        foreach($users as $user){
            if($user->user_level==2) $i++;
        }
        return $i;
    }
}
