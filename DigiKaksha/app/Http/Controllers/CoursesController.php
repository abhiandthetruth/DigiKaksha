<?php

namespace App\Http\Controllers;
use App\User;
use App\Group;
use App\Course;
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
        //
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
        $instructors = $this->getInstructors($request->input('instructors'));
        $groups = $this->getGroups($request->input('classes'));
        $course = new Course();
        $course->name = $request->input('course-name');
        $course->course_code = $request->input('course-code');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
