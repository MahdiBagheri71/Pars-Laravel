<form wire:submit.prevent="create" style="text-align: center;">

    <div>

        @if (session()->has('message'))

            <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                {{ session('message') }}

            </div>

        @endif

    </div>

    <div class="form-group">
        <label>{{__('Name')}}</label>
        <input wire:model="name" type="text" class="form-control" placeholder="{{__('Name')}}">
        @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Note')}}</label>
        <textarea wire:model="note" class="form-control"></textarea>
        @error('note') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Status')}}</label>
        <select wire:model="status" class="form-select" aria-label="{{__('Status')}}" style="text-align: center;">
            <option value="cancel" style="background : #f0077f;" >{{__('cancel')}}</option>
            <option value="success" style="background : #4cd548;" >{{__('success')}}</option>
            <option value="retarded" style="background : #eecd18;">{{__('retarded')}}</option>
            <option value="doing" style="background : #2094fb;">{{__('doing')}}</option>
            <option value="planned" style="background : #04a1bb;" selected>{{__('planned')}}</option>
            <option value="delete" style="background : #bf565b;">{{__('delete')}}</option>
            @if(Auth::user()->can('add tasks'))
                <option value="delete" style="background : #bf565b;">{{__('delete')}}</option>
            @endif
        </select>
        @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Date')}}<span id="dateCreateSpan"></span></label>
        <input class="form-control text-center" wire:model="date" id="dateCreate" placeholder="{{__('Date')}}">
        @error('date') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Time')}}<span id="timeCreateSpan"></span></label>
        <input class="form-control text-center" wire:model="time"  id="timeCreate"  class="form-control" placeholder="{{__('Time')}}">
        @error('time') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    @if(Auth::user()->can(['add tasks']))
        <div class="form-group">
            <label>{{__('User')}}</label>
            <select wire:model="user_id" class="form-select" aria-label="{{__('User')}}" style="text-align: center;">
                @foreach ($users as $user)
                    <option value="{{$user->id}}" @selected({{Auth::user()->id == $user->id}})>{{$user->name.' '.$user->last_name}}</option>
                @endforeach
            </select>
            @error('user_id') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
    @else
        <input wire:model="user_id" value="{{Auth::user()->id}}" type="hidden" class="form-control">
        @error('user_id') <span class="error text-danger">{{ $message }}</span> @enderror
    @endif

    <br>
    <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!} type="button" class="btn btn-dark m-1 createModalClose">{{__('Cancel')}}</a>
    <button type="submit" class="btn btn-success">{{__('Save')}}</button>

</form>

