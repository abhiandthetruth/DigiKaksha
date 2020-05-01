<?php

namespace App\Http\Controllers;
use App\Group;
use App\Course;
use App\User;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = array();
        $user_level = auth()->user()->user_level;
        if($user_level==1){
            $groups = auth()->user()->groups;
        }
        elseif($user_level==2){
            $courses = auth()->user()->courses;
            foreach($courses as $course){
                $flag = false;
                foreach($course->groups as $group){
                    foreach($groups as $group1) {
                        if ($group1->group_code==$group->group_code){
                            $flag = true;
                        }
                    }
                    if(!$flag) array_push($groups, $group);
                }  
            }
        }
        else{
            $groups = Group::paginate(1);
        }
        //return $groups;
        return view('/groups/index')->with('groups', $groups);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->user_level < 3) return redirect('home');
        return view('groups/create');
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
            'group_code'=>'unique:groups',
        ]);
        try{
            $students = $this->getStudents($request->input('students'));
        }
        catch(\Exception $e){
            return redirect()->back()->withInput()->withErrors(['students'=>'Some of the listed students are non-existent']);
        }
        $group = new Group();
        $group->name = $request->input('class-name');
        $group->group_code = $request->input('group_code');
        $group->save();
        $group->users()->attach($students);
        return redirect('/groups/create')->with('status', 'Group created!');
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
        if(auth()->user()->user_level < 3) return redirect('home');
        $group = Group::find($id);
        $group->delete();
        return redirect('/groups')->with('status', 'Class Deleted');
    }

    //helper functions
    public function getStudents($str){
        $batches = explode(";", $str);
        $list = array();
        foreach($batches as $batch){
            $prefix = explode(":", $batch)[0];
            $rollpatterns = explode(",", explode(":", $batch)[1]);
            foreach($rollpatterns as $rollpattern){
                $rollnos = explode("-", $rollpattern);
                if(count($rollnos)>1){
                    $upper = (int)$rollnos[0];
                    $lower = (int)$rollnos[1];
                    for($i = $upper; $i <= $lower; $i++){
                        if(strlen(strval($i)) == strlen($rollnos[0])) array_push($list, (User::where("roll_no", $prefix.$i)->first())->id);
                        else{
                            $r = "";
                            $r .= $prefix;
                            for($j = 0; $j < strlen($rollnos[0]) - strlen(strval($i)); $j++) $r.= "0";
                            $r.=$i;
                            array_push($list, (User::where("roll_no", $r)->first())->id);
                        } 
                    }
                }
                else array_push($list, (User::where("roll_no", $prefix.$rollnos[0])->first())->id);
            }
        }
        return $list;
    }
}
