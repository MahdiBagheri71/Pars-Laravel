<div>

    {{--        create task for admin--}}
    @if (Auth::user()->canany(['add tasks','add me tasks']))
        <a href="{{ route('taskCreate') }}" type="button" class="btn btn-primary m-1">{{__('Create')}}</a>
    @endif

    {{--                        show alert message--}}
    <div>
        @if ($message)

            <div class="alert alert-{{$message_type}}">

                {{ $message }}

            </div>

        @endif

        @if ($errors_message)
            <div class="alert alert-danger">
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
        <table class="table table-bordered table-striped">
            <thead>
{{--                for show header &sorting--}}
                <tr>
                    <th scope="col">#</th>
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
                    <th scope="col">
                        <a href="#" wire:click="orderBy('status')">
                            {{__('Status')}}
                            @includeWhen( $order_by == 'status', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('date')">
                            {{__('Date')}}
                            @includeWhen( $order_by == 'date', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">
                        <a href="#" wire:click="orderBy('time')">
                            {{__('Time')}}
                            @includeWhen( $order_by == 'time', 'dashboard.task.order', ['order' => $order])
                        </a>
                    </th>
                    <th scope="col">{{__('User')}}</th>
                    <th scope="col">{{__('Create By')}}</th>
                    <th scope="col">{{__('Action')}}</th>
                </tr>

{{--for fiter tasks--}}
                <tr>
                    <th scope="col"></th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_tasks.name" type="text" placeholder="{{__('Name')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_tasks.note" type="text" placeholder="{{__('Note')}}"/>
                    </th>
                    <th scope="col">
                        <select wire:model="search_tasks.status" class="form-select custom-select" aria-label="{{__('Status')}}" style="text-align: center;">
                            <option value="" style="background : #fff;">{{__('Select')}}</option>
                            <option value="cancel" style="background : #f0077f;">{{__('cancel')}}</option>
                            <option value="success" style="background : #4cd548;">{{__('success')}}</option>
                            <option value="retarded" style="background : #eecd18;">{{__('retarded')}}</option>
                            <option value="doing" style="background : #2094fb;">{{__('doing')}}</option>
                            <option value="planned" style="background : #04a1bb;">{{__('planned')}}</option>
                            <option value="delete" style="background : #bf565b;">{{__('delete')}}</option>
                        </select>
                    </th>
                    <th scope="col">
                        <input readonly="readonly" class="readonly form-control text-center" wire:model="search_tasks.date_start" id="startDate" placeholder="{{__('Date Start')}}"/>
                        <input readonly="readonly" class="readonly form-control text-center" wire:model="search_tasks.date_end" id="endDate" placeholder="{{__('Date End')}}"/>
                    </th>
                    <th scope="col">
                        <input readonly="readonly" class="readonly form-control text-center" wire:model="search_tasks.time_start" id="startTime" placeholder="{{__('Time Start')}}"/>
                        <input readonly="readonly" class="readonly form-control text-center" wire:model="search_tasks.time_end" id="endTime" placeholder="{{__('Time End')}}"/>
                    </th>
                    <th scope="col">
                        @if (Auth::user()->hasRole('admin'))
                            <select wire:model="search_tasks.user_id" class="custom-select form-select" aria-label="{{__('User')}}" style="text-align: center;">
                                <option value="">{{__('Select')}}</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}}">{{$user->name.' '.$user->last_name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </th>
                    <th scope="col">
                        @if (Auth::user()->hasRole('admin'))
                            <select wire:model="search_tasks.create_by" class="custom-select form-select" aria-label="{{__('Create By')}}" style="text-align: center;">
                                <option value="">{{__('Select')}}</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}}">{{$user->name.' '.$user->last_name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </th>
                    <th scope="col"></th>
                </tr>

            </thead>
            <tbody>
{{--            show data task--}}
            @foreach ($tasks as $row=>$task)
                <tr>
                    <th scope="row">{{$row+1}}</th>
                    <td>{{$task->name}}</td>
                    <td style="white-space: pre-wrap; white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;">{!! $task->note !!}</td>
                    <td>{{__($task->status)}}</td>
                    <td>
                        {{\Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($task->date))}}
{{--                        <br>--}}
{{--                        {{$task->date}}--}}
                    </td>
                    <td>{{$task->time}}</td>
                    <td>{{$task->user->user_name . ' ' .$task->user->user_last_name}}</td>
                    <td>{{$task->creator->creator_name . ' ' .$task->creator->creator_last_name}}</td>
                    <td>
{{--                        edit task --}}
                        @if (Auth::user()->canany('edit me task','edit status tasks','edit all tasks'))
                            <a  wire:click="showModal({{$task->id}},'edit')" type="button" class="text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                </svg>
                            </a>
                        @endif
{{--                        if admin delete for ever taks--}}
                        @if (Auth::user()->can('delete tasks'))
                            <a wire:click="showModal({{$task->id}},'delete')" type="button" class="text-danger" >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                                </svg>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Modal Delete-->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            {{__("Delete")}}
                        </h5>
                    </div>
                    <div class="modal-body">
                        {{__('Are you sure you want to delete this resource?')}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary deleteModalClose" >{{__('No')}}</button>
                        <button type="button" class="btn btn-danger deleteModalClose" wire:click="delete({{$modal_task_id}})">{{__('Yes')}}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit-->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            {{__("Edit")}}
                        </h5>
                    </div>
                    <div class="modal-body">
                        @if($modal_task)
                            @if(Auth::user()->is_admin == 1)
                                {{--                        live wire edite taks--}}
                                @livewire('edit-task',['task'=>$modal_task,'users'=>$users,'live_wire'=>true])
                            @else
                                {{--                        live wire edite status taks--}}
                                @livewire('edit-status-task',['task'=>$modal_task])
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

{{--        paginate--}}
        <div class="col-md-12">
            {{ $tasks->links() }}
        </div>

    </div>
</div>

