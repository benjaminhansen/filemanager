@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="list-group">
            @forelse(auth()->user()->my_departments() as $department)
                <a href="{{ url('departments/'.$department->id) }}" class="list-group-item">
                    {{ $department->name }}
                </a>
            @empty
                <div class="list-group-item list-group-item-warning">
                    You have no Departments assigned to your account.
                </div>
            @endforelse
        </div>
    </div>
</div>
@stop
