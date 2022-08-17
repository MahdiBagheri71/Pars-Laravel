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
        <input wire:model="user_data.name" type="text" class="form-control" placeholder="{{__('Name')}}">
        @error('user_data.name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Last Name')}}</label>
        <input wire:model="user_data.last_name" type="text" class="form-control" placeholder="{{__('Last Name')}}">
        @error('user_data.last_name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Email Address')}}</label>
        <input wire:model="user_data.email" type="email" class="form-control" placeholder="{{__('Email Address')}}">
        @error('user_data.email') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Username')}}</label>
        <input wire:model="user_data.username" type="text" class="form-control" placeholder="{{__('Username')}}">
        @error('user_data.username') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Password')}}</label>
        <input wire:model="user_data.password" type="password" class="form-control" placeholder="{{__('Password')}}">
        @error('user_data.password') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Confirm Password')}}</label>
        <input wire:model="user_data.password_confirmation" type="password" class="form-control" placeholder="{{__('Confirm Password')}}">
    </div>

    <div class="form-group">
        <label>{{__('Role')}}</label>
        <select multiple wire:model="user_data.role" class="form-select" aria-label="{{__('Role')}}" style="text-align: center;">
            @foreach ($roles as $role)
                <option value="{{$role}}">{{$role}}</option>
            @endforeach
        </select>
        @error('user_data.role') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <br>
    <a {!! $live_wire ?'': 'href="'.route('usersList').'"' !!} type="button" class="btn btn-dark m-1 createModalClose">{{__('Cancel')}}</a>
    <button type="submit" class="btn btn-success">{{__('Register')}}</button>

</form>
