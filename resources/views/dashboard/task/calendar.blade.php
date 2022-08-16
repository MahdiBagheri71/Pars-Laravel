@extends('layouts.app')

@section('js_header')
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}" />

    <script src="{{ asset('js/jquery.min.js') }}"></script>

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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    @livewireScripts
    @stack('scripts')
@endsection


@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- live wire show taks calendar--}}
                @livewire('calendar-task')
            </div>
        </div>
    </div>
    @stack('scripts')
@endsection

@section('js_end')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.3.1/main.min.js'></script>
@endsection
