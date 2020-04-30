@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Course') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="POST" action="/courses">
                        @csrf

                        <div class="form-group row">
                            <label for="course-name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="course-name" type="text" class="form-control @error('course-name') is-invalid @enderror" name="course-name" value="{{ old('course-name') }}" required autocomplete="course-name" autofocus>

                                @error('course-name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="semester" class="col-md-4 col-form-label text-md-right">{{ __('Semester') }}</label>

                            <div class="col-md-6">
                                <input id="semester" type="text" class="form-control @error('semester') is-invalid @enderror" name="semester" value="{{ old('semester') }}" required autocomplete="semester">

                                @error('semester')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="course_code" class="col-md-4 col-form-label text-md-right">{{ __('Course Code') }}</label>

                            <div class="col-md-6">
                                <input id="course_code" type="text" class="form-control @error('course_code') is-invalid @enderror" name="course_code" value="{{ old('course_code') }}" required autocomplete="course_code">

                                @error('course_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="instructors" class="col-md-4 col-form-label text-md-right">{{ __('Instructors') }}</label>

                            <div class="col-md-6">
                                <input id="instructors" type="text" class="form-control @error('instructors') is-invalid @enderror" name="instructors" value="{{ old('instructors') }}" required autocomplete="instructors">

                                @error('instructors')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="classes" class="col-md-4 col-form-label text-md-right">{{ __('Classes') }}</label>

                            <div class="col-md-6">
                                <input id="classes" type="text" class="form-control @error('classes') is-invalid @enderror" name="classes" value="{{ old('classes') }}" required autocomplete="classes">

                                @error('classes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
