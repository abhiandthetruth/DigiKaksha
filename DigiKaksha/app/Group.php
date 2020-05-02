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
    public function courses()
    {
        return $this->belongsToMany('App\Course');
    }
    public function __toString() {
        return $this->group_code;
    }
    public function getAverageMarks(){
        $i=0;
        $j=0;
        foreach($this->users as $user){
            $i+=$user->averageMarks();
            $j++;
        }
        return $i/$j;
    }
    public function getTotalEvaluations(){
        $i=0;
        foreach($this->courses as $course){
            $i+=$course->evaluationNo();
        }
        return $i;
    }
}
