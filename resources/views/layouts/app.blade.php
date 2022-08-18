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
    </style>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('js_header')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
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
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
{{--                                    ({{Auth::user()->hasRole('admin') ? __('Admin') :  __('Employee') }})--}}
                                </a>

                                <div class="text-center dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

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

                                    @if(Auth::user()->hasRole('admin'))
                                        <a class="dropdown-item" href="{{ route('usersListDelete') }}">
                                            {{ __('List Users Delete') }}
                                        </a>
                                    @endif

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
</body>
</html>
