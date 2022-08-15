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
                        > {{ __('Task') }}
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->canany(['edit me task','edit all tasks']))
                            {{--                        live wire edite taks--}}
                            @livewire('edit-task',['task'=>$task,'users'=>$users])
                        @elseif(Auth::user()->can('edit status tasks'))
                            {{--                        live wire edite status taks--}}
                            @livewire('edit-status-task',['task'=>$task])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
