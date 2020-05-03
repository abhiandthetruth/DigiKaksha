@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Class') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="/groups">
                        @csrf

                        <div class="form-group row">
                            <label for="class-name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="class-name" type="text" class="form-control @error('class-name') is-invalid @enderror" name="class-name" value="{{ old('class-name') }}" required autocomplete="class-name" autofocus>

                                @error('class-name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="group_code" class="col-md-4 col-form-label text-md-right">{{ __('Class ID') }}</label>

                            <div class="col-md-6">
                                <input id="group_code" type="text" class="form-control @error('group_code') is-invalid @enderror" name="group_code" value="{{ old('group_code') }}" required autocomplete="group_code">

                                @error('group_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="students" class="col-md-4 col-form-label text-md-right">{{ __('Student IDs') }}</label>

                            <div class="col-md-6">
                                <input id="students" type="text" class="form-control @error('students') is-invalid @enderror" name="students" value="{{ old('students') }}" required autocomplete="group_code">

                                @error('students')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
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
