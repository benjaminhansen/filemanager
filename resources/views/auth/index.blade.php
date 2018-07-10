@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <form method="POST" action="{{ url('login') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}" autofocus>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Log In</button>
            </div>
        </form>
    </div>
</div>
@stop
