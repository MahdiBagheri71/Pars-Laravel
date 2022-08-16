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
@endsection


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{--live wire show taks--}}
                @livewire('show-tasks',['deleted' => $delete])
            </div>
        </div>
    </div>
@endsection

@section('js_end')
    <script src="{{ asset('js/flatpickr/task.js') }}"></script>
@endsection
