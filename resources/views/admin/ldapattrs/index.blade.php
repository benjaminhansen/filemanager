@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <form method="POST" action="{{ url('admin/ldap-attributes') }}">
            @csrf

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
</div>
@stop
