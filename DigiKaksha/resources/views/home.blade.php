@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($user->user_level==3)
                        You are logged in as Admin!
                    @elseif ($user->user_level==2)
                        You are logged in as teacher!
                    @else
                        You are logged in as Student!
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
