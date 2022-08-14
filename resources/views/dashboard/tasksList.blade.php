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
                    <div class="card-header">
                        <a class="nav-link" href="{{ route('tasksList') }}">{{ __('Tasks') }}</a>
                    </div>
                    <div class="card-body">

                        <div>

                            @if (session()->has('message'))

                                <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                                    {{ session('message') }}

                                </div>

                            @endif

                        </div>
{{--                        live wire show taks--}}
                        @livewire('show-tasks')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
