<?php

namespace App\Http\Controllers;
use App\Course;
use App\Announcement;
use Illuminate\Http\Request;
class AnnouncementsController extends Controller
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
        if(auth()->user()->user_level < 2) return redirect('home');
        $course = Course::find(\Request::get('course'));
        if(!$this->checkEditAccess($course)) return redirect('home');
        return view('announcements/create')->with('course', $course);
    }

   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->user_level < 2) return redirect('home');
        $course = Course::where('course_code', $request->input('course-code'))->first();
        $announcement = new Announcement();
        $announcement->title = $request->input('title');
        $announcement->body = $request->input('body');
        if($request->input('graded')) {
            $announcement->graded=1;
            $announcement->max_grade = $request->input('max_grade');
            $announcement->component = $request->input('component');
        }
        else $announcement->graded=0;
        $announcement->user_id = auth()->user()->id;
        $announcement->course_id = $course->id;
        $announcement->save();
        return redirect('/courses'.'/'.$course->id)->with('status','Announcement made!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->user_level < 1) return redirect('home');
        $announcement = Announcement::find($id);
        if(!($this->checkViewAccess($announcement->course) or $this->checkEditAccess($announcement->course))) return redirect('home');
        return view('announcements/show')->with('announcement', $announcement);
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

    //helper functions
    public function checkEditAccess($course)
    {
        $flag = false;
        foreach($course->users as $user) {
            if($user->id==auth()->user()->id or auth()->user()->user_level == 3){
                $flag = true;
                break;
            }
        }
        return $flag;
    }

    public function checkViewAccess($course)
    {
        $flag = false;
        foreach(auth()->user()->groups as $group) {
            foreach($group->courses as $course1){
                if($course1->id==$course->id){
                    $flag = true;
                    break;
                }
            }
        }
        return $flag;
    }
}
