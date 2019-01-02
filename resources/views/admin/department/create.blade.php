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

            <h4>LDAP Group DNs</h4>
            @foreach($permissions as $permission)

                <div class="form-group">
                    <strong>{{ $permission->name }}</strong><br />
                    <input type="text" name="permission_{{ $permission->id }}" class="form-control" required>
                </div>

            @endforeach
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ url('admin/departments') }}" title="Cancel" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@stop
