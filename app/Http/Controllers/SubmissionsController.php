<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubmissionsController extends Controller
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
        if(auth()->user()->user_level >= 2) return redirect('home');
        $announcement = \App\Announcement::find(\Request::get('announcement'));
        if(!$this->isValid($announcement)) return redirect('home');
        return view('submissions/create')->with('announcement', $announcement);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->user_level >= 2) return redirect('home');
        $announcement = \App\Announcement::find($request->input('announcement'));
        if(!$this->isValid($announcement)) return redirect('home');
        foreach(auth()->user()->submissions as $submission){
            if($submission->announcement->id==$announcement->id)
                return redirect()->back()->with('status', 'A submission has already been made');
        }
        $submission = new \App\Submission();
        $submission->announcement_id = $request->input('announcement');
        $submission->body = $request->input('body');
        $submission->user_id = auth()->user()->id;
        $submission->save();
        return redirect('/announcements'.'/'.$announcement->id)->with('status', 'Submission done!');
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
        if(auth()->user()->user_level < 2) return redirect('home');
        $submission = \App\Submission::find($id);
        if(!$this->checkEditAccess($submission->announcement->course)) return redirect('home');
        return view('submissions/edit')->with('submission', $submission);
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
        if(auth()->user()->user_level < 2) return redirect('home');
        $submission = \App\Submission::find($id);
        if(!$this->checkEditAccess($submission->announcement->course)) return redirect('home');
        $submission->grade = $request->input('grade');
        $submission->comment = $request->input('comment');
        $submission->save();
        return redirect('/announcements'.'/'.$submission->announcement->id)->with('status', 'Submission for '.$submission->user->roll_no.' graded succesfully!');
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

    public function isValid($announcement)
    {
        $flag = false;
        $course = $announcement->course;
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
}
