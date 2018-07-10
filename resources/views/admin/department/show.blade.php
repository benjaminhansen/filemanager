@extends('layouts.app')

@section('content')
<p><a href="{{ url('admin/departments/'.$department->id.'/edit') }}" title="Back" class="btn btn-success">Edit</a></p>

<div class="row">
    <div class="col-md-4">
        <div class="card bg-light text-center">
            <div class="card-body">
                <h4><strong>Number of Files</strong>: {{ $department->files->count() }}</h4>
            </div>
            <a href="{{ url('admin/departments/'.$department->id.'/files') }}" class="card-footer">
                Manage Files
            </a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-light text-center">
            <div class="card-body">
                <h4><strong>Number of Users</strong>: {{ $department->users->count() }}</h4>
            </div>
            <a href="{{ url('admin/departments/'.$department->id.'/users') }}" class="card-footer">
                Manage Users
            </a>
        </div>
    </div>
</div>
@stop
