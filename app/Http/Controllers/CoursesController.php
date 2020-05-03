<?php

namespace App\Http\Controllers;
use App\User;
use App\Group;
use App\Course;
use App\attendance;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = array();
        $user_level = auth()->user()->user_level;
        if($user_level == 1){
            $groups = auth()->user()->groups;
            foreach($groups as $group){
                foreach($group->courses as $course){
                    $flag = false;
                    foreach($courses as $course1){
                        if($course1->id==$course->id){
                            $flag=true;
                            break;
                        }
                    }
                    if(!$flag) array_push($courses, $course);
                }
            }
        }
        elseif ($user_level == 2) {
            $courses = auth()->user()->courses;
        }
        else{
            $courses = Course::paginate(10);
        }
        return view('courses/index')->with('courses',$courses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(auth()->user()->user_level < 3) return redirect('home');
        return view('courses/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->user_level < 3) return redirect('home');
        $this->validate($request, [
            'course_code'=>'unique:courses',
        ]);
        try{
            $instructors = $this->getInstructors($request->input('instructors'));
        }
        catch(\Exception $e){
            return redirect()->back()->withInput()->withErrors(['instructors'=>'Some of the instructors are non-existent']);
        }
        try{
            $groups = $this->getGroups($request->input('classes'));
        }
        catch(\Exception $e){
            return redirect()->back()->withInput()->withErrors(['classes'=>'Some of the groups are non-existent']);
        }
        $course = new Course();
        $course->name = $request->input('course-name');
        $course->course_code = $request->input('course_code');
        $course->semester = $request->input('semester');
        $course->save();
        $course->users()->attach($instructors);
        $course->groups()->attach($groups);
        return redirect('/courses/create')->with('status', 'Course created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $course = Course::find($id);
        if(auth()->user()->user_level > 1){
        return view('courses/show')->with('course', $course);
        }
        else{
            $classId = 0; 
            $flag=0;
            $groups= auth()->user()->groups;
            foreach($groups as $group){
                if($flag==1)
                 break;
              foreach($group->courses as $co)
              {
                           if($co->id == $id){
                           $classId = $group->id;
                           $flag=1;
                           break;
                           }
               }
            }
            $roll=auth()->user()->roll_no;
            $attendances = attendance::where('course_id', $id)->where('group_id',$classId)->get();
            $markedAttendances = [];
            $present =0;
            $total=0;
            foreach($attendances as $att){
                $total++;
               $thisAtt = new \stdClass;
               $thisAtt->date = $att->date;
               $thisAtt->taker= $att->taker;
               $presentStudents = explode(" ", $att->present);
               if(array_search($roll,$presentStudents) === false){     
                       $thisAtt->present = false;
               }else{
                       $present++;
                       $thisAtt->present = true;
               }
               array_push($markedAttendances,$thisAtt);
            }
            return view('courses/show',compact('course','markedAttendances','present','total'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->user_level < 3) return redirect('home');
        $course = Course::find($id);
        return view('courses/edit')->with('course', $course);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->user_level < 3) return redirect('home');
        $course = Course::find($id);
        if($course->course_code != $request->input('course_code'))
            $this->validate($request, [
                'course_code'=>'unique:courses',
            ]);
        try{
            $instructors = $this->getInstructors($request->input('instructors'));
        }
        catch(\Exception $e){
            return redirect()->back()->withInput()->withErrors(['instructors'=>'Some of the instructors are non-existent']);
        }
        try{
            $groups = $this->getGroups($request->input('classes'));
        }
        catch(\Exception $e){
            return redirect()->back()->withInput()->withErrors(['classes'=>'Some of the groups are non-existent']);
        }
        $course->name = $request->input('course-name');
        $course->course_code = $request->input('course_code');
        $course->semester = $request->input('semester');
        $course->save();
        $oldInstructors = array_map(function($i){return $i['id'];}, $course->users()->get()->toArray());
        $oldGroups = array_map(function($i){return $i['id'];}, $course->groups()->get()->toArray());
        $course->users()->detach($oldInstructors);
        $course->groups()->detach($oldGroups);
        $course->users()->attach($instructors);
        $course->groups()->attach($groups);
        return redirect('/courses'.'/'.$course->id)->with('status', 'Course Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->user_level < 3) return redirect('home');
        $course = Course::find($id);
        $oldInstructors = array_map(function($i){return $i['id'];}, $course->users()->get()->toArray());
        $oldGroups = array_map(function($i){return $i['id'];}, $course->groups()->get()->toArray());
        $course->users()->detach($oldInstructors);
        $course->groups()->detach($oldGroups);
        $course->delete();
        return redirect('/courses')->with('status', 'Course Deleted');
    }

    public function getInstructors($str)
    {
        $instructorRolls = explode(",", $str);
        $list = array();
        foreach($instructorRolls as $instructorRoll) array_push($list, (User::where('roll_no',$instructorRoll)->first())->id);
        return $list;
    }

    public function getGroups($str)
    {
        $groupCodes = explode(",", $str);
        $list = array();
        foreach($groupCodes as $groupCode) array_push($list, (Group::where('group_code',$groupCode)->first())->id);
        return $list;
    }
}
