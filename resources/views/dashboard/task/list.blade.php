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
@endsection


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{--live wire show tasks--}}
                @livewire('show-tasks',['deleted' => $delete])
            </div>
        </div>
    </div>
@endsection

@section('js_end')
    <script src="{{ asset('js/flatpickr/task.js') }}"></script>
@endsection
