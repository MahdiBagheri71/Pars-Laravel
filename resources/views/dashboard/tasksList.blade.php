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
                    <div class="card-header">{{ __('Tasks') }}</div>
                    <div class="card-body">
{{--                        live wire show taks--}}
                        @livewire('show-tasks')
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
