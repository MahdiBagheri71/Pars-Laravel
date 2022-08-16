@extends('layouts.app')

@section('js_header')
    @livewireStyles
    @livewireScripts
@endsection

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{--live wire show users--}}
                @livewire('show-users',['deleted' => $delete])
            </div>
        </div>
    </div>
@endsection
