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
        @if($task->status != 'delete')
            <select wire:model="status" class="form-select" aria-label="{{__('Status')}}" style="text-align: center;">
                <option value="cancel" class="text-cancel"  {{$task->status == 'cancel' ? 'selected':''}}>{{__('cancel')}}</option>
                <option value="success" class="text-success" {{$task->status == 'success' ? 'selected':''}} >{{__('success')}}</option>
                <option value="retarded" class="text-retarded" {{$task->status == 'retarded' ? 'selected':''}}>{{__('retarded')}}</option>
                <option value="doing" class="text-doing" {{$task->status == 'doing' ? 'selected':''}}>{{__('doing')}}</option>
                <option value="planned" class="text-planned"  {{$task->status == 'planned' ? 'selected':''}}>{{__('planned')}}</option>
            </select>
            @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
        @else
            <div>{{__($task->status)}}</div>
        @endif
    </div>

    <br>
    <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!} type="button" class="btn btn-dark m-1 editStatusModalClose">{{__('Cancel')}}</a>

</form>

