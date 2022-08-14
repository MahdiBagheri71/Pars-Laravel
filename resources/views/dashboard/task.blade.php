@extends('layouts.app')

@section('js_header')
    @livewireStyles
    @livewireScripts
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
                        @if(Auth::user()->is_admin == 1)
                            {{--                        live wire show taks--}}
                            @livewire('edit-task',['task'=>$task,'users'=>$users])
                        @else
                            {{--                        live wire show taks--}}
                            @livewire('edit-status-task',['task'=>$task])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
