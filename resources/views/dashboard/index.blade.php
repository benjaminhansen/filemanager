@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            My Recent Uploads
          </div>
          <div class="card-body">
              <div class="list-group">
                  @foreach(auth()->user()->my_recent_uploads() as $file)
                    <div class="list-group-item">
                        <strong>{{ $file->name }}<br /><small>{{ $file->created_at }}</small></strong><br /><a href="{{ url('departments/'.$file->department()->id) }}">{{ $file->department()->name }}</a>
                    </div>
                  @endforeach
              </div>
          </div>
        </div>
    </div>
    @if(auth()->user()->hasPermission('global_administrator') || auth()->user()->hasPermission('department_administrator'))
        <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                Recent Uploads In My Departments
              </div>
              <div class="card-body">
                  <div class="list-group">
                      @foreach(auth()->user()->my_departments_recent_uploads() as $file)
                          <div class="list-group-item">
                              <strong>{{ $file->name }}<br /><small>{{ $file->created_at }}</small></strong><br /><a href="{{ url('departments/'.$file->department()->id) }}">{{ $file->department()->name }}</a>
                          </div>
                      @endforeach
                  </div>
              </div>
            </div>
        </div>
    @endif
    <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            My Departments
          </div>
          <div class="card-body">
              <div class="list-group">
                  @foreach(auth()->user()->my_departments() as $dept)
                    <a class="list-group-item" href="{{ url('departments/'.$dept->id) }}">
                        {{ $dept->name }}
                    </a>
                  @endforeach
              </div>
          </div>
        </div>
    </div>
</div>
@stop
