@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <form method="POST" action="{{ url('admin/departments/'.$department->id.'/groups') }}">
            {{ csrf_field() }}

            @foreach($permissions as $permission)

                <div class="form-group">
                    <strong>{{ $permission->name }}</strong><br />
                    <input type="text" name="{{ $department->id }}_{{ $permission->id }}" class="form-control" value="{{ $permission->ldapGroup($department->id) }}">
                </div>

            @endforeach

            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ url('admin/departments/'.$department->id) }}" title="Cancel" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@stop
