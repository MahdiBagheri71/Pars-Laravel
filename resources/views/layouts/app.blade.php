<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{(App::isLocale('fa') ? 'rtl' : 'ltr')}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'PEYDA';
            src: url('{{ asset('font/PEYDA-BOLD.ttf') }}');
            src: local('BYekan'),  url('{{ asset('font/PEYDA-BOLD.ttf') }}') format('truetype');
        }
        *{
            font-family: 'PEYDA';
        }
        a.notification-user-row{
            padding: 10px;
        }
        li.notification-user-row{
            background: #f7f7f7;
            margin: 2px;
        }
    </style>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('js_header')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}" title="{{ config('app.name', 'Laravel') }}">
                    <img src="{{asset('img/logo.png')}}" height="40" style="40px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else

                            <li class="nav-item dropdown">
                                <a id="task_list_show_socket_a" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
                                        {{__('Tasks')}}
                                    </svg>
                                    <span id="number_task_list" class="position-absolute top-50 start-100 translate-middle badge rounded-pill bg-secondary rounded-circle"></span>
                                </a>

                                <div id="task_list_show_socket" style="overflow: auto;max-height: 200px;padding: 5px;"  class="text-center dropdown-menu  dropdown-menu-start" aria-labelledby="navbarDropdown">

                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
{{--                                    ({{Auth::user()->hasRole('admin') ? __('Admin') :  __('Employee') }})--}}
                                </a>

                                <div class="text-center dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" role="menu">

                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        {{ __('Dashboard') }}
                                    </a>

{{--                                    <a class="dropdown-item" href="{{ route('tasksCalendar') }}">--}}
{{--                                        {{ __('Calendar Tasks') }}--}}
{{--                                    </a>--}}

                                    @if(Auth::user()->canany(['view tasks', 'view all tasks']))
                                        <a class="dropdown-item" href="{{ route('tasksList') }}">
                                            {{ __('List Tasks') }}
                                        </a>
                                    @endif

                                    @if(Auth::user()->canany(['view tasks', 'view all tasks']))
                                        <a class="dropdown-item" href="{{ route('tasksFullCalendar') }}">
                                            {{ __('Calendar Tasks') }}
                                        </a>
                                    @endif

                                    @if(Auth::user()->canany(['view tasks', 'view all tasks']))
                                        <a class="dropdown-item" href="{{ route('tasksKanban') }}">
                                            {{ __('Kanban Tasks') }}
                                        </a>
                                    @endif

                                    @if(Auth::user()->hasRole('admin'))
                                        <a class="dropdown-item" href="{{ route('tasksStatus') }}">
                                            {{ __('List Status Task') }}
                                        </a>
                                    @endif
{{--                                    @if(Auth::user()->hasRole('admin'))--}}
{{--                                        <a class="dropdown-item" href="{{ route('tasksListDelete') }}">--}}
{{--                                            {{ __('List Tasks Delete') }}--}}
{{--                                        </a>--}}
{{--                                    @endif--}}

                                    @if(Auth::user()->hasRole('admin'))
                                        <a class="dropdown-item" href="{{ route('usersList') }}">
                                            {{ __('List Users') }}
                                        </a>
                                    @endif

{{--                                    @if(Auth::user()->hasRole('admin'))--}}
{{--                                        <a class="dropdown-item" href="{{ route('usersListDelete') }}">--}}
{{--                                            {{ __('List Users Delete') }}--}}
{{--                                        </a>--}}
{{--                                    @endif--}}

                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        {{ __('Profile') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('js_end')

    @if(Auth::check())
        <!-- Scripts -->
        <script>
            var SITEURL = "{{ url('/') }}";
            var user_id = {{Auth::id()}};
        </script>
        <script src="{{ asset('js/WebSocket.js') }}" defer></script>
    @endif
</body>
</html>
