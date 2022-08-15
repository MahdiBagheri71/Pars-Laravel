<form wire:submit.prevent="submit" style="text-align: center;">

    <div>

        @if (session()->has('message'))

            <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                {{ session('message') }}

            </div>

        @endif

    </div>

    <div class="form-group">
        <label>{{__('Name')}}</label>
        <input wire:model="name" value="{{$task->name}}" type="text" class="form-control" placeholder="{{__('Name')}}">
        @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Note')}}</label>
        <textarea wire:model="note" class="form-control">{{$task->note}}</textarea>
        @error('note') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Status')}}</label>
        <select wire:model="status" class="form-select" aria-label="{{__('Status')}}" style="text-align: center;">
            <option value="cancel" style="background : #f0077f;" @selected({{$task->status == 'cancel' }} )>{{__('cancel')}}</option>
            <option value="success" style="background : #4cd548;" @selected({{$task->status == 'success'}}) >{{__('success')}}</option>
            <option value="retarded" style="background : #eecd18;" @selected({{$task->status == 'retarded'}})>{{__('retarded')}}</option>
            <option value="doing" style="background : #2094fb;" @selected({{$task->status == 'doing'}})>{{__('doing')}}</option>
            <option value="planned" style="background : #04a1bb;" @selected({{$task->status == 'planned'}})>{{__('planned')}}</option>

            @if(Auth::user()->can('edit all tasks'))
                <option value="delete" style="background : #bf565b;" @selected({{$task->status == 'delete'}})>{{__('delete')}}</option>
            @endif
        </select>
        @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Date')}}</label>
        <input wire:model="date" value="{{$task->date}}" class="form-control text-center dateEdit" placeholder="{{__('Date')}}">
        @error('date') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Time')}}</label>
        <input wire:model="time" value="{{$task->time}}" class="form-control text-center timeEdit" placeholder="{{__('Time')}}">
        @error('time') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    @if(Auth::user()->can('edit all tasks'))
        <div class="form-group">
            <label>{{__('User')}}</label>
                <select wire:model="user_id" class="form-select" aria-label="{{__('User')}}" style="text-align: center;">
                    @foreach ($users as $user)
                        <option value="{{$user->id}}" @selected({{$task->user_id == $user->id}})>{{$user->name.' '.$user->last_name}}</option>
                    @endforeach
                </select>
                @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
    @endif

    <br>

    <div class="modal-footer">
        <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!}  type="button" class="btn btn-dark m-1 editModalClose">{{__('Cancel')}}</a>
        <button type="submit" class="btn btn-success m-1">{{__('Save')}}</button>
    </div>

</form>

