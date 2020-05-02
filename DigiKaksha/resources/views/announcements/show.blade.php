@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><h5 class="display-3 mb-0">{{ $announcement->title }}</h5>
                    <small class="text-muted inintialism">By {{$announcement->user->name}} at {{$announcement->created_at}} in 
                        <a href="/courses/{{$announcement->course->id}}">{{$announcement->course->name}}</a>
                        </small>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <p>{{$announcement->body}}</p>
                </div>
                @if($announcement->graded==1)
                    <div class="card-footer">
                        @if(Auth::user()->user_level==1)
                            @foreach(Auth::user()->groups as $group)
                                @foreach($group->courses as $course)
                                    @if($course->id==$announcement->course->id)
                                        <a href="#!" class="btn btn-sm btn-primary">Add Submission</a>
                                        @break
                                    @endif
                                @endforeach
                            @endforeach
                        @elseif(Auth::user()->user_level==3)
                            <a href="#!" class="btn btn-sm btn-primary">View Submissions</a>
                        @else
                            @foreach(Auth::user()->courses as $course)
                                @if($course->id==$announcement->course->id)
                                    <a href="#!" class="btn btn-sm btn-primary">View Submissions</a>
                                    @break
                                @endif
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</div>
@endsection
