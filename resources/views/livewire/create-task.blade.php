<form wire:submit.prevent="create(Object.fromEntries(new FormData($event.target)))" class="text-center">

    <div>

        @if (session()->has('message'))

            <div class="message-create alert alert-{{session('type') == 'error' ? 'danger':'success'}}">
                <button type="button" class="btn-close message-create-close" data-bs-dismiss="alert" aria-label="Close"></button>
                {{ session('message') }}

            </div>

        @endif

    </div>

    <div class="form-group">
        <label>{{__('Name')}}</label>
        <input name="name" type="text" value="{{$task_data['name']}}" class="form-control resetCloseCreate" placeholder="{{__('Name')}}">
        @error('task_data.name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Note')}}</label>
        <textarea name="note" class="form-control resetCloseCreate">{{$task_data['note']}}</textarea>
        @error('task_data.note') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Status')}}</label>
        <select name="status" class="form-select text-center" aria-label="{{__('Status')}}">
            @foreach($tasks_status as $key=>$status)
                <option value="{{$key}}" class="text-{{$key}}" {{$key== 'planned' ? 'selected':''}}>{{__($status['label'])}}</option>
            @endforeach
        </select>
        @error('task_data.status') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Date')}}</label>
        <input class="form-control text-center" value="{{$task_data['date']}}" name="date" id="dateCreate" placeholder="{{__('Date')}}">
        @error('task_data.date') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Time')}}</label>
        <input class="form-control text-center" value="{{$task_data['time']}}" name="time"  id="timeCreate"  class="form-control" placeholder="{{__('Time')}}">
        @error('task_data.time') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    @if(Auth::user()->can(['add tasks']))
        <div class="form-group">
            <label>{{__('User')}}</label>
            <select name="user_id" class="form-select" aria-label="{{__('User')}}" style="text-align: center;">
                @foreach ($users as $user)
                    <option value="{{$user->id}}" @selected({{Auth::user()->id == $user->id}})>{{$user->name.' '.$user->last_name}}</option>
                @endforeach
            </select>
            @error('task_data.user_id') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
    @else
        <input name="user_id" value="{{Auth::user()->id}}" type="hidden" class="form-control">
        @error('task_data.user_id') <span class="error text-danger">{{ $message }}</span> @enderror
    @endif

    <br>
    <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!} type="button" class="btn btn-dark m-1 createModalClose">{{__('Cancel')}}</a>
    <button type="submit" class="btn btn-success">{{__('Save')}}</button>

</form>

