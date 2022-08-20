<div class="card">
    <div id="spinner_status" class="spinner-border text-warning" role="status" style="position: fixed;left: 48%;z-index: 999999999;top: 48%;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="card-header">
        <div class="nav-link float-end m-1">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{$deleted ?  __('List Status Task Delete') : __('List Status Task') }}({{$tasks->total()}})
                    </li>
                </ol>
            </nav>
        </div>


        <a wire:click="refresh" type="button"  title="{{__("Refresh")}}" class="text-dark float-start m-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-counterclockwise"><path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"></path> <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"></path></svg>
        </a>
        {{--create task for admin--}}
        <a wire:click="showModal(0,'create')" type="button" title="{{__("Create")}}" class="text-primary float-start m-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
            </svg>
        </a>
    </div>
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a wire:click="setDeleted(0)" class="nav-link {{$deleted ?  '' : 'active' }}" aria-current="true" href="#"> {{  __('List Status Task') }}</a>
            </li>
            @if(Auth::user()->hasRole('admin'))
                <li class="nav-item">
                    <a wire:click="setDeleted(1)" class="nav-link {{$deleted ?  'active' : '' }}" href="#">{{ __('List Status Task Delete')}}</a>
                </li>
            @endif
        </ul>
    </div>

    <div class="card-body">

        {{--show alert message--}}
        <div>
            @if (session()->has('message'))

                <div class="alert alert-{{$message_type}}">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('message') }}
                </div>

            @endif

            @if ($errors_message)
                <div class="alert alert-danger">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <ul>
                        @foreach ($errors_message as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-md-12 text-center table-responsive">

            {{-- table list tasks--}}
            <table class="table-responsive table table-bordered table-striped table-hover">
                <thead>
                {{--for show header & sorting--}}
                <tr>
                    <th scope="col">
                        #
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('value')">
                            {{__('Value')}}
                            @includeWhen( $order_by == 'value', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('label')">
                            {{__('Label')}}
                            @includeWhen( $order_by == 'label', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col" style="min-width: 100px;">
                        <a href="#" wire:click="orderBy('color')">
                            {{__('Color')}}
                            @includeWhen( $order_by == 'color', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">{{__('Translate')}}</th>
                    <th scope="col">{{__('Action')}}</th>
                </tr>

                {{--for fiter tasks--}}
                <tr>
                    <th scope="col"></th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_status.value" type="text"
                               placeholder="{{__('Value')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_status.label" type="text"
                               placeholder="{{__('Label')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_status.color" type="text"
                               placeholder="{{__('Color')}}"/>
                    </th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>

                </thead>
                <tbody>
                {{--show data task--}}
                @foreach ($tasks as $row=>$task)
                    <tr>
                        {{--Show row by start & pagination--}}
                        <th scope="row">{{$row+($tasks->firstItem())}}</th>
                        <td>{{$task->value}}</td>
                        <td>{{$task->label}}</td>
                        <td style="direction: ltr;color:{{$task->color}}">{{$task->color}}</td>
                        <td>{{__($task->label)}}</td>
                        <td>
                                @if(!$deleted)
                                    <a title="{{__("Edit")}}" wire:click="showModal({{$task->id}},'edit')" type="button"
                                       class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path
                                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                    <a  title="{{__("Delete")}}" wire:click="showModal({{$task->id}},'delete')" type="button" class="text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                                        </svg>
                                    </a>
                                @endif

                                @if(Auth::user()->hasRole('admin') && $deleted)
                                    <a wire:click="showModal({{$task->id}},'restore')"  title="{{__("Restore")}}"  type="button" class="text-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                             class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                  d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                            <path
                                                d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                        </svg>
                                    </a>
                                @endif
                            </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <!-- Modal Delete-->
            <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
                 aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{__("Delete")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            {{__('Are you sure you want to delete this resource?')}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary deleteModalClose">{{__('No')}}</button>
                            <button type="button" class="btn btn-danger deleteModalClose"
                                    wire:click="delete({{$modal_status_id}})">{{__('Yes')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit-->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalCenterTitle"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{__("Edit")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            @if($modal_status)
                                {{--live wire edit taks--}}
                                @livewire('edit-status',['task_status'=>$modal_status,'live_wire'=>true])
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Create-->
            <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
                 aria-labelledby="createModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{__("Status Task Create")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            @if($modal_status_create)
                                @livewire('create-status-task',['live_wire'=>true])
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Restore-->
            <div class="modal fade" id="restoreModal" tabindex="-1" role="dialog"
                 aria-labelledby="restoreModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{__("Restore")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            {{__('Are you sure you want to restore this resource?')}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary restoreModalClose">{{__('No')}}</button>
                            <button type="button" class="btn btn-success restoreModalClose"
                                    wire:click="restore({{$modal_status_id}})">{{__('Yes')}}</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card-footer">
        {{--        paginate--}}
        <div class="col-md-12">
            {{ $tasks->links() }}
        </div>
    </div>

</div>
