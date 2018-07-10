@extends('layouts.app')

@section('content')
<department-users departmentid="{{ $department->id }}"></department-users>
@stop
