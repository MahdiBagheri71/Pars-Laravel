<form wire:submit.prevent="submit" style="text-align: center;">

    <div>

        @if (session()->has('message'))

            <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                {{ session('message') }}

            </div>

        @endif

    </div>

    <div class="form-group">
        <label>{{__('Status')}} : </label>
        <select wire:model="status" class="form-select text-center" aria-label="{{__('Status')}}">
            @foreach(config('enums.task_status') as $key=>$task_status)
                <option value="{{$key}}" class="text-{{$key}}" {{$key== $task->status ? 'selected':''}}>{{__($task_status['label'])}}</option>
            @endforeach
        </select>
        @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

    <br>
    <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!} type="button" class="btn btn-dark m-1 editStatusModalClose">{{__('Cancel')}}</a>

</form>

