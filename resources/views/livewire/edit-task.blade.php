<form wire:submit.prevent="submit(Object.fromEntries(new FormData($event.target)))" style="text-align: center;">

    <div>

        @if (session()->has('message'))

            <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                {{ session('message') }}

            </div>

        @endif

    </div>

    <div class="form-group">
        <label>{{__('Name')}}</label>
        <input name="name" value="{{$task_data['name']}}" type="text" class="form-control" placeholder="{{__('Name')}}">
        @error('task_data.name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Note')}}</label>
        <textarea name="note" class="form-control">{{$task_data['note']}}</textarea>
        @error('task_data.note') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Status')}}</label>
        <select name="status" class="form-select" aria-label="{{__('Status')}}" style="text-align: center;">
            <option value="cancel" style="background : #f0077f;" @selected({{$task_data['status'] == 'cancel' }} )>{{__('cancel')}}</option>
            <option value="success" style="background : #4cd548;" @selected({{$task_data['status'] == 'success'}}) >{{__('success')}}</option>
            <option value="retarded" style="background : #eecd18;" @selected({{$task_data['status'] == 'retarded'}})>{{__('retarded')}}</option>
            <option value="doing" style="background : #2094fb;" @selected({{$task_data['status'] == 'doing'}})>{{__('doing')}}</option>
            <option value="planned" style="background : #04a1bb;" @selected({{$task_data['status'] == 'planned'}})>{{__('planned')}}</option>

            @if(Auth::user()->can('edit all tasks'))
                <option value="delete" style="background : #bf565b;" @selected({{$task_data['status'] == 'delete'}})>{{__('delete')}}</option>
            @endif
        </select>
        @error('task_data.status') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Date')}}</label>
        <input name="date" value="{{$task_data['date']}}" class="form-control text-center dateEdit" placeholder="{{__('Date')}}">
        @error('task_data.date') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Time')}}</label>
        <input name="time" value="{{$task_data['time']}}" class="form-control text-center timeEdit" placeholder="{{__('Time')}}">
        @error('task_data.time') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    @if(Auth::user()->can('edit all tasks'))
        <div class="form-group">
            <label>{{__('User')}}</label>
                <select name="user_id" class="form-select" aria-label="{{__('User')}}" style="text-align: center;">
                    @foreach ($users as $user)
                        <option value="{{$user->id}}" @selected({{$task_data['user_id'] == $user->id}})>{{$user->name.' '.$user->last_name}}</option>
                    @endforeach
                </select>
                @error('task_data.status') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
    @endif

    <br>

    <div class="modal-footer">
        <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!}  type="button" class="btn btn-dark m-1 editModalClose">{{__('Cancel')}}</a>
        <button type="submit" class="btn btn-success m-1">{{__('Save')}}</button>
    </div>

</form>

