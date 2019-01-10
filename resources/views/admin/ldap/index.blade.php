@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <h4>Attributes</h4>
        <form method="POST" action="{{ url('admin/ldap') }}">
            @csrf
            <input type="hidden" name="ldap_update" value="attributes">

            @foreach($attributes as $attribute)
                <div class="form-group">
                    <label>{{ $attribute->local_attribute }}</label>
                    <input type="text" name="{{ $attribute->local_attribute }}" class="form-control" value="{{ $attribute->ldap_attribute }}" required>
                </div>
            @endforeach

            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ url('/') }}" title="Cancel" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <h4>Global Administrators Group</h4>
        <form method="POST" action="{{ url('admin/ldap') }}">
            @csrf
            <input type="hidden" name="ldap_update" value="admins">

            <div class="form-group">
                <label>Group DN</label>
                <input type="text" name="group_dn" value="{{ $admin_group->ldap_group_dn }}" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ url('/') }}" title="Cancel" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@stop
