<div class="card">
    <div id="spinner_task" class="spinner-border text-warning" role="status" style="position: fixed;left: 48%;z-index: 999999999;top: 48%;">
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
                        {{$deleted ?  __('List Tasks Delete') : __('List Tasks') }}({{$tasks->total()}})
                    </li>
                </ol>
            </nav>
        </div>


        <a wire:click="refresh" type="button"  title="{{__("Refresh")}}" class="text-dark float-start m-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-counterclockwise"><path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"></path> <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"></path></svg>
        </a>
        {{--create task for admin--}}
        @if (Auth::user()->canany(['add tasks','add me tasks']))
            <a wire:click="showModal(0,'create')" type="button" title="{{__("Create")}}" class="text-primary float-start m-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
            </a>
        @endif
    </div>
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a wire:click="setDeleted(0)" class="nav-link {{$deleted ?  '' : 'active' }}" aria-current="true" href="#"> {{  __('List Tasks') }}</a>
            </li>
            @if(Auth::user()->hasRole('admin'))
                <li class="nav-item">
                    <a wire:click="setDeleted(1)" class="nav-link {{$deleted ?  'active' : '' }}" href="#">{{ __('List Tasks Delete')}}</a>
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

            {{--        tabel list taks--}}
            <table class="table-responsive table table-bordered table-striped table-hover">
                <thead>
                {{--                for show header &sorting--}}
                <tr>
                    <th scope="col">
{{--                        <a href="#" wire:click="orderBy('id')">--}}
{{--                            #--}}
{{--                            @includeWhen( $order_by == 'id', 'dashboard.task.order', ['order' => $order])--}}
{{--                        </a>--}}
                        #
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('name')">
                            {{__('Name')}}
                            @includeWhen( $order_by == 'name', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('note')">
                            {{__('Note')}}
                            @includeWhen( $order_by == 'note', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col" style="min-width: 100px;">
                        <a href="#" wire:click="orderBy('status')">
                            {{__('Status')}}
                            @includeWhen( $order_by == 'status', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('date')">
                            {{__('Date Start')}}
                            @includeWhen( $order_by == 'date', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('time')">
                            {{__('Time Start')}}
                            @includeWhen( $order_by == 'time', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">{{__('User')}}</th>
                    <th scope="col">{{__('Create By')}}</th>
                    @if(Auth::user()->canany(['edit me task','edit all tasks','delete tasks']) || Auth::user()->hasRole('admin') && $deleted)
                        <th scope="col">{{__('Action')}}</th>
                    @endif
                </tr>

                {{--for fiter tasks--}}
                <tr>
                    <th scope="col"></th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_tasks.name" type="text"
                               placeholder="{{__('Name')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_tasks.note" type="text"
                               placeholder="{{__('Note')}}"/>
                    </th>
                    <th scope="col">
                        <select wire:model="search_tasks.status" class="form-select custom-select"
                                aria-label="{{__('Status')}}" style="text-align: center;">
                            <option value="" style="background : #fff;">{{__('All')}}</option>
                            @foreach($tasks_status as $key=>$task_status)
                                <option value="{{$key}}" class="text-{{$key}}">{{__($task_status['label'])}}</option>
                            @endforeach
                        </select>
                    </th>
                    <th scope="col">
                        <input readonly="readonly" class="readonly form-control text-center"
                               wire:model="search_tasks.date_start" id="startDate" placeholder="{{__('Date Start')}}"/>
                        <input readonly="readonly" class="readonly form-control text-center"
                               wire:model="search_tasks.date_end" id="endDate" placeholder="{{__('Date End')}}"/>
                    </th>
                    <th scope="col">
                        <input readonly="readonly" class="readonly form-control text-center"
                               wire:model="search_tasks.time_start" id="startTime" placeholder="{{__('Time Start')}}"/>
                        <input readonly="readonly" class="readonly form-control text-center"
                               wire:model="search_tasks.time_end" id="endTime" placeholder="{{__('Time End')}}"/>
                    </th>
                    <th scope="col">
                        @if (Auth::user()->hasRole('admin'))
                            <select wire:model="search_tasks.user_id" class="custom-select form-select"
                                    aria-label="{{__('User')}}" style="text-align: center;">
                                <option value="">{{__('All')}}</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}}">{{$user->name.' '.$user->last_name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </th>
                    <th scope="col">
                        @if (Auth::user()->hasRole('admin'))
                            <select wire:model="search_tasks.create_by" class="custom-select form-select"
                                    aria-label="{{__('Create By')}}" style="text-align: center;">
                                <option value="">{{__('All')}}</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}}">{{$user->name.' '.$user->last_name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </th>
                    @if(Auth::user()->canany(['edit me task','edit all tasks','delete tasks']) || Auth::user()->hasRole('admin') && $deleted)
                        <th scope="col"></th>
                    @endif
                </tr>

                </thead>
                <tbody>
                {{--            show data task--}}
                @foreach ($tasks as $row=>$task)
                    <tr>
                        {{--                    Show row by start & pagination--}}
                        <th scope="row">{{$row+($tasks->firstItem())}}</th>
                        <td>{{$task->name}}</td>
                        <td style="white-space: pre-wrap; white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;">{!! substr($task->note,0,25).(strlen($task->note)>25?' ...':'') !!}</td>
                        <td class="text-{{$task->status}}" {!! (Auth::user()->can('edit status tasks') && !$deleted) ? ' type="button" wire:click="showModal('.$task->id.',\'edit_status\')"':''  !!}>
                            {{__(isset($tasks_status[$task->status])?$tasks_status[$task->status]['label']:$task->status)}}
                        </td>
                        <td>{!! \Morilog\Jalali\CalendarUtils::strftime('l d F Y', strtotime($task->date_start))  !!}</td>
                        <td>{{$task->time_start}}</td>
                        <td>{{$task->user->user_name . ' ' .$task->user->user_last_name}}</td>
                        <td>{{$task->creator->creator_name . ' ' .$task->creator->creator_last_name}}</td>
                        @if(Auth::user()->canany(['edit me task','edit all tasks','delete tasks']) || Auth::user()->hasRole('admin') && $deleted)
                            <td>
                            @if(!$deleted)
                                {{--edit task --}}
                                @if (Auth::user()->canany(['edit me task','edit all tasks']))
                                    <a title="{{__("Edit")}}" wire:click="showModal({{$task->id}},'edit')" type="button"
                                       class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path
                                                d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                        </svg>
                                    </a>
                                @endif
                                {{--                        if admin delete for ever taks--}}
                                @if (Auth::user()->can('delete tasks'))
                                    <a  title="{{__("Delete")}}" wire:click="showModal({{$task->id}},'delete')" type="button" class="text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                                        </svg>
                                    </a>
                                @endif
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
                        @endif
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
                                    wire:click="delete({{$modal_task_id}})">{{__('Yes')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit-->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalCenterTitle"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    @if($modal_task)
                        @if(Auth::user()->canany(['edit me task','edit all tasks']))
                            {{--                        live wire edit taks--}}
                            @livewire('edit-task',['task'=>$modal_task,'users'=>$users,'live_wire'=>true])
                        @endif
                    @endif
                </div>
            </div>

            <!-- Modal Edit Status-->
            <div class="modal fade" id="editStatusModal" tabindex="-1" role="dialog" aria-labelledby="editStatusModalCenterTitle"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{__("Edit")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            @if($modal_task)
                                @if(Auth::user()->can('edit status tasks'))
                                    {{--live wire edit status taks--}}
                                    @livewire('edit-status-task',['task'=>$modal_task,'live_wire'=>true])
                                @endif
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
                                {{__("Task Create")}}
                            </h5>
                        </div>
                        <div class="modal-body">
                            @if($modal_task_create)
                                @if(Auth::user()->canany(['add tasks','add me tasks']))
                                    @livewire('create-task',['users'=>$users,'live_wire'=>true])
                                @endif
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
                                    wire:click="restore({{$modal_task_id}})">{{__('Yes')}}</button>
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
