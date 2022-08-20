<form wire:submit.prevent="create(Object.fromEntries(new FormData($event.target)))" style="text-align: center;">

    <div>

        @if (session()->has('message'))

            <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">
                <button type="button" class="btn-close message-create-close" data-bs-dismiss="alert" aria-label="Close"></button>

                {{ session('message') }}

            </div>

        @endif

    </div>

    <div class="form-group">
        <label>{{__('Name')}}</label>
        <input name="name" type="text" value="{{$user_data['name']}}" class="form-control resetCloseCreate" placeholder="{{__('Name')}}">
        @error('user_data.name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Last Name')}}</label>
        <input name="last_name" type="text" value="{{$user_data['last_name']}}" class="form-control resetCloseCreate" placeholder="{{__('Last Name')}}">
        @error('user_data.last_name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Email Address')}}</label>
        <input name="email" type="email" value="{{$user_data['email']}}" class="form-control resetCloseCreate" placeholder="{{__('Email Address')}}">
        @error('user_data.email') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Username')}}</label>
        <input name="username" type="text" value="{{$user_data['username']}}" class="form-control resetCloseCreate" placeholder="{{__('Username')}}">
        @error('user_data.username') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Password')}}</label>
        <input name="password" type="password" class="form-control resetCloseCreate" placeholder="{{__('Password')}}">
        @error('user_data.password') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Confirm Password')}}</label>
        <input name="password_confirmation" type="password" class="form-control resetCloseCreate" placeholder="{{__('Confirm Password')}}">
    </div>

    <div class="form-group">
        <label>{{__('Role')}}</label>
        <select multiple wire:model="role_select" class="form-select" aria-label="{{__('Role')}}" style="text-align: center;">
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
