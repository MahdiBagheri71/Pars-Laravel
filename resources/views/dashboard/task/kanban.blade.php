@extends('layouts.app')

@section('js_header')
    @livewireStyles
    @livewireScripts

    <style>
        .card.draggable {
            margin-bottom: 1rem;
            cursor: grab;
        }

        .droppable {
            background-color: var(--success);
            min-height: 120px;
            margin-bottom: 1rem;
        }


        @foreach($tasks_status as $key=>$status)

            .text-{{$key}} {
                --bs-text-opacity: 1;
                color: {{$status['color']}} !important;
            }

            .text-bg-{{$key}} {
                --bs-text-opacity: 1;
                background-color: {{$status['color']}} !important;
                color: #ffffff !important;
            }
        @endforeach
    </style>
@endsection
@section('content')
    <div class="col-md-11" style="margin: 0 auto;">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @livewire('kanban-task',['tasks_status'=>$tasks_status])

            </div>
        </div>
    </div>
    @stack('scripts')
@endsection
