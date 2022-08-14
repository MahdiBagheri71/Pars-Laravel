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
            <option value="cancel" style="background : #f0077f;" {{$task->status == 'cancel' ? 'selected':''}}>{{__('cancel')}}</option>
            <option value="success" style="background : #4cd548;" {{$task->status == 'success' ? 'selected':''}} >{{__('success')}}</option>
            <option value="retarded" style="background : #eecd18;" {{$task->status == 'retarded' ? 'selected':''}}>{{__('retarded')}}</option>
            <option value="doing" style="background : #2094fb;" {{$task->status == 'doing' ? 'selected':''}}>{{__('doing')}}</option>
            <option value="planned" style="background : #04a1bb;" {{$task->status == 'planned' ? 'selected':''}}>{{__('planned')}}</option>
            <option value="delete" style="background : #bf565b;" {{$task->status == 'delete' ? 'selected':''}}>{{__('delete')}}</option>
        </select>
        @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Date')}}</label>
        <input wire:model="date" value="{{$task->date}}" type="date" class="form-control" placeholder="{{__('Date')}}">
        @error('date') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Time')}}</label>
        <input wire:model="time" value="{{$task->time}}" type="time" class="form-control" placeholder="{{__('Time')}}">
        @error('time') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('User')}}</label>
        <select wire:model="user_id" class="form-select" aria-label="{{__('User')}}" style="text-align: center;">
            @foreach ($users as $user)
                <option value="{{$user->id}}}" {{$task->user_id == $user->id ? 'selected':''}}>{{$user->name.' '.$user->last_name}}</option>
            @endforeach
        </select>
        @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <br>

    <button type="submit" class="btn btn-success">{{__('Save')}}</button>

</form>

