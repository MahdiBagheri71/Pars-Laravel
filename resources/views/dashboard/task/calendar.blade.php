@extends('layouts.app')

@section('js_header')
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}" />

    <script src="{{ asset('js/moment.min.js') }}"></script>

    <script src="{{ asset('js/moment-jalaali.js') }}"></script>

    <script src="{{ asset('js/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('js/fa.js') }}"></script>

    <link href="{{ asset('css/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/flatpickr/rtl.css') }}" rel="stylesheet">
    <script src="{{ asset('js/flatpickr/jdate.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr/rangePlugin.js') }}"></script>
    <script src="{{ asset('js/flatpickr/fa.js') }}"></script>

    @livewireScripts
    <style>
        .flatpickr-wrapper{
            display: block !important;
        }
        .breadcrumb-item + .breadcrumb-item::before {
            float: right;
            padding-left: var(--bs-breadcrumb-item-padding-x);
            color: var(--bs-breadcrumb-divider-color);
            content: var(--bs-breadcrumb-divider, "/");
        }

        @foreach($tasks_status as $key=>$status)

        .text-{{$key}} {
            --bs-text-opacity: 1;
            color: {{$status['color']}} !important;
        }
        @endforeach

    </style>
    @stack('scripts')
@endsection


@section('content')

    <div class="container">
        {{-- live wire show taks calendar--}}
        @livewire('calendar-task')
    </div>
    @stack('scripts')
@endsection
