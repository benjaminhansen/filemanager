<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ url('/').mix('css/app.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css" />

        <title>{{ $title }} | File Manager</title>

        <script>
            window.Laravel = {!! json_encode(['app_url' => url('/'), 'current_user_id' => auth()->user() == null ? 0 : auth()->user()->id]) !!};
        </script>
    </head>
    <body>
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-light fixed-top navbar-laravel">
              <a class="navbar-brand" href="{{ url('/') }}">Rise Vision File Manager</a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                @if(auth()->check())
                    <ul class="navbar-nav mr-auto">
                      @if(count(auth()->user()->my_departments()) > 0)
                          <li class="nav-item">
                            <a class="nav-link" href="{{ url('departments') }}">Departments</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="https://accounts.google.com/o/oauth2/auth?redirect_uri=https%3A%2F%2Fapps.risevision.com%2F&response_type=permission%20id_token&scope=email%20profile%20openid&openid.realm=&client_id=614513768474.apps.googleusercontent.com&ss_domain=https%3A%2F%2Frisevision.com&prompt=select_account&fetch_basic_profile=true&gsiwebsdk=2" target="_blank">Access Rise Vision</a>
                          </li>
                      @endif
                      @if(auth()->user()->hasPermission('global_administrator'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/departments') }}">Manage Departments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/ldap-attributes') }}">Manage LDAP Attributes</a>
                        </li>
                      @endif
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ auth()->user()->fname }} {{ auth()->user()->lname }}</a>
                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
                            <h6 class="dropdown-header">{{ auth()->user()->username }}</h6>
                            <a class="dropdown-item" href="{{ url('logout') }}">Logout</a>
                          </div>
                        </li>
                    </ul>
                @endif
              </div>
            </nav>

            <main role="main" class="container-fluid">
                <h2 class="page-title">{{ $title }}</h2>
                {!! Helpers::message() !!}
                @yield('content')
            </main>

            <footer>
                <div class="container-fluid">
                    <hr />
                    <h6>&copy;{{ date("Y") }} {{ env('APP_NAME') }}. All Rights Reserved.</h6>
                </div>
            </footer>
        </div>
        <script src="{{ url('/').mix('js/app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
    </body>
</html>
