@extends('layouts.app')

@section('js_header')
    @livewireStyles
    @livewireScripts
    <script src="{{ asset('js/jquery.min.js') }}"></script>
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

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{--live wire show tasks--}}
                @livewire('show-status-task',['deleted' => $delete])
            </div>
        </div>
    </div>
@endsection

@section('js_end')
    <script src="{{ asset('js/status.js') }}"></script>
@endsection
