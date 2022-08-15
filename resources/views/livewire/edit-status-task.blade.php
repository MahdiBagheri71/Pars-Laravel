<form wire:submit.prevent="submit" style="text-align: center;">

    <div>

        @if (session()->has('message'))

            <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                {{ session('message') }}

            </div>

        @endif

    </div>

    <div class="form-group">
        <label>{{__('Name')}} : </label>
        <div>{{$task->name}}</div>
    </div>

    <div class="form-group">
        <label>{{__('Note')}} : </label>
        <div style="white-space: pre-wrap; white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;">
            {{$task->note}}
        </div>
    </div>

    <div class="form-group">
        <label>{{__('Status')}} : </label>
        @if($task->status != 'delete')
            <select wire:model="status" class="form-select" aria-label="{{__('Status')}}" style="text-align: center;">
                <option value="cancel" style="background : #f0077f;" {{$task->status == 'cancel' ? 'selected':''}}>{{__('cancel')}}</option>
                <option value="success" style="background : #4cd548;" {{$task->status == 'success' ? 'selected':''}} >{{__('success')}}</option>
                <option value="retarded" style="background : #eecd18;" {{$task->status == 'retarded' ? 'selected':''}}>{{__('retarded')}}</option>
                <option value="doing" style="background : #2094fb;" {{$task->status == 'doing' ? 'selected':''}}>{{__('doing')}}</option>
                <option value="planned" style="background : #04a1bb;" {{$task->status == 'planned' ? 'selected':''}}>{{__('planned')}}</option>
            </select>
            @error('status') <span class="error text-danger">{{ $message }}</span> @enderror
        @else
            <div>{{__($task->status)}}</div>
        @endif
    </div>

    <div class="form-group">
        <label>{{__('Date')}} : </label>
        <div>{{$task->date}}</div>
    </div>

    <div class="form-group">
        <label>{{__('Time')}} : </label>
        <div>{{$task->time}}</div>
    </div>

    <br>
    <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!} type="button" class="btn btn-dark m-1 editModalClose">{{__('Cancel')}}</a>
    @if($task->status != 'delete')
        <button type="submit" class="btn btn-success m-1">{{__('Save')}}</button>
    @endif

</form>

