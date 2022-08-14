@extends('layouts.app')

@section('js_header')
    @livewireStyles
    @livewireScripts
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

@endsection
