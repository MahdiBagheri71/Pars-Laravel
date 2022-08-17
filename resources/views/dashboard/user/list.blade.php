@extends('layouts.app')

@section('js_header')
    @livewireStyles
    @livewireScripts
    <script src="{{ asset('js/jquery.min.js') }}"></script>
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

@section('js_end')
    <script src="{{ asset('js/user.js') }}"></script>
@endsection
