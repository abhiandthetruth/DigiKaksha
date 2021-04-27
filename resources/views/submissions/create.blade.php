@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Make Submission') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="POST" action="/submissions" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label for="announce" class="col-md-4 col-form-label text-md-right">{{ __('Announcement') }}</label>

                            <div class="col-md-8">
                                <input id="announce" type="text" class="form-control @error('announce') is-invalid @enderror" name="announce" value="{{ $announcement->title }}" required autocomplete="announce" disabled>

                                @error('announce')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="body" class="col-md-4 col-form-label text-md-right">{{ __('Body') }}</label>

                            <div class="col-md-8">
                                <textarea id="body" style="height:200px" type="text" class="form-control @error('body') is-invalid @enderror" name="body" value="{{ old('body') }}" autocomplete="body"></textarea>

                                @error('body')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="photos[]" class="col-md-4 col-form-label text-md-right">Photos(This will override body if given)</label>
                            <input type="file" class="form-control col-md-4 col-form-label text-md-right" name="photos[]" multiple />
                        </div>
                        <input type="hidden" name="announcement" value="{{ $announcement->id }}">
                        
                        {{-- <div class="dropzone dropzone-single form-group row" data-toggle="dropzone" data-dropzone-url="http://">
                            <div class="fallback">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="dropzoneBasicUpload" name="file">
                                    <label class="custom-file-label" for="dropzoneBasicUpload">Choose file</label>
                                </div>
                            </div>
                        </div> --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
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
