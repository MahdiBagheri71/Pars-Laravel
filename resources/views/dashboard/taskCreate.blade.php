@extends('layouts.app')

@section('js_header')
    @livewireStyles
    @livewireScripts
    <link href="{{ asset('css/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/flatpickr/rtl.css') }}" rel="stylesheet">
    <script src="{{ asset('js/flatpickr/jdate.min.js') }}"></script>
    <script>window.Date=window.JDate;</script>
    <script src="{{ asset('js/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr/rangePlugin.js') }}"></script>
    <script src="{{ asset('js/flatpickr/fa.js') }}"></script>


    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <style>

        label{
            font-weight: bolder !important;
        }
    </style>
@endsection


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <a class="nav-link" href="{{ route('tasksList') }}">{{ __('Tasks') }}</a>
                        > {{ __('Task Create') }}
                    </div>
                    <div class="card-body">
                        {{--                        live wire show taks--}}
                        @livewire('create-task',['users'=>$users])
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    flatpickr("#dateCreate", {
        enableTime: false,
        "locale": "fa" ,
        noCalendar: false,
        time_24hr: true,
        dateFormat: "Y-m-d"
    });



    flatpickr("#timeCreate", {
        enableTime: true,
        "locale": "fa" ,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i"
    });
</script>
@endsection
