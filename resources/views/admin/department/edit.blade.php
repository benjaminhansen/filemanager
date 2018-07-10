@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <form method="POST" action="{{ url('admin/departments/'.$department->id) }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group">
                <label>Department Name</label>
                <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
            </div>
            <div class="form-group">
                <label>Enable/Disable Department</label>
                <select name="status" class="form-control">
                    <option value="1" @if($department->enabled) selected @endif>Enabled</option>
                    <option value="0" @if(!$department->enabled) selected @endif>Disabled</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ url('admin/departments/'.$department->id) }}" title="Cancel" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        <hr />
        <form method="POST" action="{{ url('admin/departments/'.$department->id) }}">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <div class="form-group">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete Department</button>
            </div>
        </form>
    </div>
</div>
@stop
