@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('View Submission') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                        <h5 class="display-4">{{$submission->announcement->title}}</h5>
                        <small>by {{$submission->user->roll_no}}</small>
                        <br><br>
                        <p><strong>Submission: </strong>{{$submission->body}}</p>
                        
                        <br>
                    <form method="POST" action="/submissions/{{$submission->id}}">
                        @csrf

                        <div class="form-group row">
                            <label for="grade" class="col-md-2 col-form-label text-md-right">{{ __('Grade') }}</label>

                            <div class="col-md-8">
                                <input id="grade" type="number" class="form-control @error('grade') is-invalid @enderror" name="grade" value="{{ $submission->grade }}" required autocomplete="grade" autofocus>

                                @error('grade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="comment" class="col-md-2 col-form-label text-md-right">{{ __('Comment') }}</label>

                            <div class="col-md-8">
                                <textarea id="comment" style="height:200px" type="text" class="form-control @error('comment') is-invalid @enderror" name="comment" value="{{ $submission->body }}" required autocomplete="comment"></textarea>

                                @error('comment')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" name="_method" value="put">
                        
                        {{-- <div class="dropzone dropzone-single form-group row" data-toggle="dropzone" data-dropzone-url="http://">
                            <div class="fallback">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="dropzoneBasicUpload" name="file">
                                    <label class="custom-file-label" for="dropzoneBasicUpload">Choose file</label>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-5">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit!') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
