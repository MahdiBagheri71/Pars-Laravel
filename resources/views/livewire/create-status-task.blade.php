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
        <label>{{__('Value')}}</label>
        <input name="value" type="text" value="{{$task_status_data['value']}}" class="form-control resetCloseCreate" placeholder="{{__('Value')}}">
        @error('task_status.value') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Label')}}</label>
        <input name="label" type="text" value="{{$task_status_data['label']}}" class="form-control resetCloseCreate" placeholder="{{__('Label')}}">
        @error('task_status.label') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Color')}}</label>
        <input name="color" type="color" value="{{$task_status_data['color']}}" class="form-control resetCloseCreate" placeholder="{{__('Color')}}">
        @error('task_status.color') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <br>
    <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!} type="button" class="btn btn-dark m-1 createModalClose">{{__('Cancel')}}</a>
    <button type="submit" class="btn btn-success">{{__('Save')}}</button>

</form>

