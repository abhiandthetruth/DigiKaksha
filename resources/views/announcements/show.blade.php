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
            </div>
                @if($announcement->graded==1)
                    <div class="card">
                        @if(Auth::user()->user_level==1)
                        <div class="card-header">
                            @if(Auth::user()->hasSubmission($announcement))
                                @foreach(Auth::user()->submissions as $submission)
                                    @if($submission->announcement_id==$announcement->id)

                                        <p><strong>Submission:</strong> <small>{{$submission->body}}</small></p>
                                        <p class="strong"><strong>Grade:</strong> 
                                            @if($submission->grade)
                                                {{$submission->grade}}
                                            @else
                                                <small> No grade awarded yet</small>
                                            @endif
                                        </p>
                                        <p class="strong"><strong>Comments:</strong> 
                                            @if($submission->comment)
                                                {{$submission->comment}}
                                            @else
                                                <small>No commments</small>
                                            @endif
                                        </p>
                                        @break
                                    @endif
                                @endforeach
                            @else
                            @foreach(Auth::user()->groups as $group)
                                @foreach($group->courses as $course)
                                    @if($course->id==$announcement->course->id)
                                        <a href="/submissions/create?announcement={{$announcement->id}}" class="btn btn-sm btn-primary">Add Submission</a>
                                        @break
                                    @endif
                                @endforeach
                            @endforeach
                            @endif
                        </div>
                        @elseif(Auth::user()->user_level==3)
                            <div class="card-header">
                                Submissions
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                      <thead class="thead-light">
                                        <tr>
                                          <th scope="col" class="sort" data-sort="name">Roll No</th>
                                          <th scope="col" class="sort" data-sort="budget">Name</th>
                                          <th scope="col" class="sort" data-sort="budget">Current Grade</th>
                                          <th scope="col" class="sort" data-sort="completion"></th>
                                        </tr>
                                      </thead>
                                      <tbody class="list">
                                          @foreach($announcement->submissions as $submission)
                                              <tr>
                                                  <th scope="row">
                                                    <div class="media align-items-center">
                                                      <div class="media-body">
                                                        <span class="name mb-0 text-sm"><a>{{$submission->user->roll_no}}</a></span>
                                                      </div>
                                                    </div>
                                                  </th>
                                                  <td class="budget">
                                                    {{$submission->user->name}}
                                                  </td>
                                                  <td class="budget">
                                                    {{$submission->grade}}
                                                  </td>
                                                  <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="/submissions/{{$submission->id}}/edit" class="btn btn-sm btn-primary">View Submission</a>
                                                    </div>
                                                  </td>
                                                </tr> 
                                          @endforeach
                                      </tbody>
                                    </table>
                                  </div> 
                            </div>
                        @else
                            @foreach(Auth::user()->courses as $course)
                                @if($course->id==$announcement->course->id)
                                <div class="card-header">
                                    Submissions
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table align-items-center table-flush">
                                          <thead class="thead-light">
                                            <tr>
                                              <th scope="col" class="sort" data-sort="name">Roll No</th>
                                              <th scope="col" class="sort" data-sort="budget">Name</th>
                                              <th scope="col" class="sort" data-sort="budget">Current Grade</th>
                                              <th scope="col" class="sort" data-sort="completion"></th>
                                            </tr>
                                          </thead>
                                          <tbody class="list">
                                              @foreach($announcement->submissions as $submission)
                                                  <tr>
                                                      <th scope="row">
                                                        <div class="media align-items-center">
                                                          <div class="media-body">
                                                            <span class="name mb-0 text-sm"><a>{{$submission->user->roll_no}}</a></span>
                                                          </div>
                                                        </div>
                                                      </th>
                                                      <td class="budget">
                                                        {{$submission->user->name}}
                                                      </td>
                                                      <td class="budget">
                                                        {{$submission->grade}}
                                                      </td>
                                                      <td>
                                                        <div class="d-flex align-items-center">
                                                            <a href="/submissions/{{$submission->id}}/edit" class="btn btn-sm btn-primary">View Submission</a>
                                                        </div>
                                                      </td>
                                                    </tr> 
                                              @endforeach
                                          </tbody>
                                        </table>
                                      </div> 
                                </div>
                                </div>
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
