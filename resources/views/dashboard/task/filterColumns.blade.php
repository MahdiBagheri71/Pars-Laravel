@switch($column['columns']['type'])

    @case('select')

        <th scope="col">
            <select wire:model="search_tasks.{{$column['columns']['field']}}" class="form-select custom-select"
                    aria-label="{{__($column['columns']['label'])}}" style="text-align: center;">
                <option value="" style="background : #fff;">{{__('All')}}</option>
                @foreach($tasks_status as $key=>$task_status)
                    <option value="{{$key}}" class="text-{{$key}}">{{__($task_status['label'])}}</option>
                @endforeach
            </select>
        </th>
    @break

    @case('string')
    @case('text')
        <th scope="col">
            <input class="form-control" wire:model="search_tasks.{{$column['columns']['field']}}" type="text" placeholder="{{__($column['columns']['label'])}}"/>
        </th>
    @break

    @case('date')
        <th scope="col">
            <input readonly="readonly" class="dateEdit readonly form-control text-center" wire:model="search_tasks.{{$column['columns']['field']}}_start" placeholder="{{__('Date Start')}}"/>
            <input readonly="readonly" class="dateEdit readonly form-control text-center" wire:model="search_tasks.{{$column['columns']['field']}}_end" placeholder="{{__('Date End')}}"/>
        </th>
    @break

    @case('time')
        <th scope="col">
            <input readonly="readonly" class="timeEdit readonly form-control text-center" wire:model="search_tasks.{{$column['columns']['field']}}_start" placeholder="{{__('Time Start')}}"/>
            <input readonly="readonly" class="timeEdit readonly form-control text-center" wire:model="search_tasks.{{$column['columns']['field']}}_end" placeholder="{{__('Time End')}}"/>
        </th>
    @break

    @case('related')
        <th scope="col">
            @if($column['columns']['related_table'] == 'users')
                @if (Auth::user()->hasRole('admin'))
                    <select wire:model="search_tasks.{{$column['columns']['field']}}" class="custom-select form-select"
                            aria-label="{{__($column['columns']['label'])}}" style="text-align: center;">
                        <option value="">{{__('All')}}</option>
                        @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name.' '.$user->last_name}}</option>
                        @endforeach
                    </select>
                @endif
            @endif
        </th>
    @break

    @case('time_tracking')
        <th scope="col" style="min-width: 100px;"></th>
    @break

    @default
        <th scope="col"></th>
    @endswitch

