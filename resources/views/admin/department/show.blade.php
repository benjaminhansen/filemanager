@extends('layouts.app')

@section('content')
<p>
    <a href="{{ url('admin/departments/'.$department->id.'/edit') }}" title="Edit" class="btn btn-success">Edit</a>
    <a href="{{ url('admin/departments/'.$department->id.'/groups') }}" title="LDAP Groups" class="btn btn-primary">LDAP Groups</a>
</p>

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
                View Users
            </a>
        </div>
    </div>
</div>
@stop
