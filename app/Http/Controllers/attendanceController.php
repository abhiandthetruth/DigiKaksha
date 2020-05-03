<?php

namespace App\Http\Controllers;
use App\User;
use App\Group;
use App\Course;
use App\attendance;
use Illuminate\Http\Request;

class attendanceController extends Controller
{
    public function processRequest(Request $request,$cid){
        if(auth()->user()->user_level == 1) return redirect('home');
        $red = "/attendance/mark/".$cid."/".$request->input("class");
        return redirect($red);
    }
    public function showForm(Request $request,$cid,$gid){
        if(auth()->user()->user_level == 1) return redirect('home');
        $group = Group::find($gid);
        return view('attendance/markAttendance',compact('group','cid','gid'));
    }
    
    public function markAttendance(Request $request,$cid,$gid){
         $att = new attendance;
         $att->date= $request->input('date');
         $att->course_id= $cid;
         $att->group_id =$gid;
         $att->taker= auth()->user()->name;
         $group = Group::find($gid);
         $str="";
         foreach ($group->users as $student)
         {
            if($request->input($student->roll_no)){
                $str.=$student->roll_no." ";
            }
         }
         $att->present = $str;
         $att->save();
         $red = "courses/".$cid;
         return redirect($red)->with('status', 'Attendance Marked');
    }
}
