@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <form method="POST" action="{{ url('admin/departments') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label>Department Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" autofocus>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ url('admin/departments') }}" title="Cancel" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@stop
