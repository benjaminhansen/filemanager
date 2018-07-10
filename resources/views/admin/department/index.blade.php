@extends('layouts.app')

@section('content')
<p><a href="{{ url('admin/departments/create') }}" title="Add New Department" class="btn btn-success">Add New Department</a></p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>URI Slug</th>
                <th>Enabled</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $department)
                <tr>
                    <td>{{ $department->id }}</td>
                    <td><a href="{{ url('admin/departments/'.$department->id) }}">{{ $department->name }}</a></td>
                    <td>{{ $department->uri }}</td>
                    <td>{{ $department->enabled ? 'Yes' : 'No' }}</td>
                    <td>{{ date("m/d/Y @ g:ia", strtotime($department->updated_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop
