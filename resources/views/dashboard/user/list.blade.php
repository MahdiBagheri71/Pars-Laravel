@extends('layouts.app')

@section('js_header')
    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @livewireScripts

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
