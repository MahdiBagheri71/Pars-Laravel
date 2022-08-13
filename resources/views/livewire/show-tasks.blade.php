<div>
{{--    <input wire:model="search_tasks" type="text" placeholder="Search tasks..."/>--}}

    <div class="col-md-12" style="text-align: center;">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('Name')}}</th>
                    <th scope="col">{{__('Note')}}</th>
                    <th scope="col">{{__('Status')}}</th>
                    <th scope="col">{{__('Date')}}</th>
                    <th scope="col">{{__('Time')}}</th>
                    <th scope="col">{{__('User')}}</th>
                    <th scope="col">{{__('Create By')}}</th>
                    <th scope="col">{{__('Action')}}</th>
                </tr>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_tasks.name" type="text" placeholder="{{__('Name')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_tasks.note" type="text" placeholder="{{__('Note')}}"/>
                    </th>
                    <th scope="col">
                        <select wire:model="search_tasks.status" class="form-select" aria-label="{{__('Status')}}" style="text-align: center;">
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
                        <input class="form-control" wire:model="search_tasks.date_start" type="date" placeholder="{{__('Date Start')}}"/>
                        <input class="form-control" wire:model="search_tasks.date_end" type="date" placeholder="{{__('Date End')}}"/>
                    </th>
                    <th scope="col">
                        <input class="form-control" wire:model="search_tasks.time_start" type="time" placeholder="{{__('Time Start')}}"/>
                        <input class="form-control" wire:model="search_tasks.time_end" type="time" placeholder="{{__('Time End')}}"/>
                    </th>
                    <th scope="col">
                        @if (Auth::user()->is_admin == 1)
                            <select wire:model="search_tasks.user_id" class="form-select" aria-label="{{__('User')}}" style="text-align: center;">
                                <option value="">{{__('Select')}}</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}}">{{$user->name.' '.$user->last_name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </th>
                    <th scope="col">
                        @if (Auth::user()->is_admin == 1)
                            <select wire:model="search_tasks.create_by" class="form-select" aria-label="{{__('Create By')}}" style="text-align: center;">
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
            @foreach ($tasks as $row=>$task)
                <tr>
                    <th scope="row">{{$row+1}}</th>
                    <td>{{$task->name}}</td>
                    <td style="white-space: pre-wrap;       /* Since CSS 2.1 */
    white-space: -moz-pre-wrap;  /* Mozilla, since 1999 */
    white-space: -pre-wrap;      /* Opera 4-6 */
    white-space: -o-pre-wrap;    /* Opera 7 */
    word-wrap: break-word;">{!! $task->note !!}</td>
                    <td>{{__($task->status)}}</td>
                    <td>{{$task->date}}</td>
                    <td>{{$task->time}}</td>
                    <td>{{$task->user_name . ' ' .$task->user_last_name}}</td>
                    <td>{{$task->creator_name . ' ' .$task->creator_last_name}}</td>
                    <td>{{$task->id}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>


        <div class="col-md-12">
            {{ $tasks->links() }}
        </div>

    </div>
</div>
