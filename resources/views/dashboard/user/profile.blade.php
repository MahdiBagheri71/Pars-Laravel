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
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header d-flex">
                        {{ __('Profile') }}
                    </div>
                    <div class="card-body">
                        {{--live wire edit user--}}
                        @livewire('edit-user',['user_id'=>$user_id , 'profile' => true])
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
