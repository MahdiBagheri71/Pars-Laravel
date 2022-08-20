<form wire:submit.prevent="submit(Object.fromEntries(new FormData($event.target)))" style="text-align: center;">

    <div>

        @if (session()->has('message'))

            <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                {{ session('message') }}

            </div>

        @endif

    </div>

    <div class="form-group">
        <label>{{__('Value')}}</label>
        <input name="value" value="{{$task_data_status['value']}}" type="text" class="form-control" placeholder="{{__('Value')}}">
        @error('task_data_status.value') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Label')}}</label>
        <input name="label" value="{{$task_data_status['label']}}" type="text" class="form-control" placeholder="{{__('Label')}}">
        @error('task_data_status.label') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label>{{__('Color')}}</label>
        <input name="color" value="{{$task_data_status['color']}}" type="color" class="form-control" placeholder="{{__('Color')}}">
        @error('task_data_status.color') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <br>

    <div class="modal-footer">
        <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!}  type="button" class="btn btn-dark m-1 editModalClose">{{__('Cancel')}}</a>
        <button type="submit" class="btn btn-success m-1">{{__('Save')}}</button>
    </div>

</form>

