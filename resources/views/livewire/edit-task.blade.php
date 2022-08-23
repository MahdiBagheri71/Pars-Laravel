<div class="modal-content">
    <div class="modal-header" style="padding: 10px;border: 0;">
        <h5 class="modal-title" style="width: 100%;">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a type="button" wire:click="setCommentsView(0)" class="nav-link {{$comments_view ?  '' : 'active' }}" aria-current="true" > {{  __('Edit Task') }}</a>
                </li>
                @if ($this->task->create_by_id == Auth::user()->id || $this->task->user_id == Auth::user()->id)
                    <li class="nav-item">
                        <a type="button" wire:click="setCommentsView(1)" class="nav-link {{$comments_view ?  'active' : '' }}">{{ __('List Comments Task')}}({{$comments->count()}})</a>
                    </li>
                @endif
            </ul>
        </h5>
    </div>
    <br>
    <div class="modal-body">
        <form wire:submit.prevent="submit(Object.fromEntries(new FormData($event.target)))" style="display: {{$comments_view ?  'none' : 'block' }}">

        <div>

            @if (session()->has('message'))

                <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                    {{ session('message') }}

                </div>

            @endif

        </div>

        <div class="form-group">
            <label>{{__('Name')}}</label>
            <input name="name" value="{{$task_data['name']}}" type="text" class="form-control" placeholder="{{__('Name')}}">
            @error('task_data.name') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>{{__('Note')}}</label>
            <textarea name="note" class="form-control">{{$task_data['note']}}</textarea>
            @error('task_data.note') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>{{__('Status')}}</label>
            <select name="status" class="form-select text-center" aria-label="{{__('Status')}}" style="text-align: center;">
                @foreach($tasks_status as $key=>$task_status)
                    <option value="{{$key}}" class="text-{{$key}}" {{$key== $task->status ? 'selected':''}}>{{__($task_status['label'])}}</option>
                @endforeach
            </select>
            @error('task_data.status') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>{{__('Date Start')}}</label>
            <input name="date_start" value="{{$task_data['date_start']}}" class="form-control text-center dateEdit" placeholder="{{__('Date')}}">
            @error('task_data.date_start') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>{{__('Time Start')}}</label>
            <input name="time_start" value="{{$task_data['time_start']}}" class="form-control text-center timeEdit" placeholder="{{__('Time')}}">
            @error('task_data.time_start') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>{{__('Date Finish')}}</label>
            <input name="date_finish" value="{{$task_data['date_finish']}}" class="form-control text-center dateEdit" placeholder="{{__('Date')}}">
            @error('task_data.date_finish') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>{{__('Time Finish')}}</label>
            <input name="time_finish" value="{{$task_data['time_finish']}}" class="form-control text-center timeEdit" placeholder="{{__('Time')}}">
            @error('task_data.time_finish') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>

        @if(Auth::user()->can('edit all tasks'))
            <div class="form-group">
                <label>{{__('User')}}</label>
                    <select name="user_id" class="form-select" aria-label="{{__('User')}}" style="text-align: center;">
                        @foreach ($users as $user)
                            <option value="{{$user->id}}" {{$task_data['user_id'] == $user->id ? 'selected':''}}>{{$user->name.' '.$user->last_name}}</option>
                        @endforeach
                    </select>
                    @error('task_data.user_id') <span class="error text-danger">{{ $message }}</span> @enderror
            </div>
        @else
            <input name="user_id" type="hidden" value="{{Auth::user()->id}}">
            @error('task_data.user_id') <span class="error text-danger">{{ $message }}</span> @enderror
        @endif

        <br>

        <div class="modal-footer">
            <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!}  type="button" class="btn btn-dark m-1 editModalClose">{{__('Cancel')}}</a>
            <button type="submit" class="btn btn-success m-1">{{__('Save')}}</button>
        </div>

    </form>
        @if ($this->task->create_by_id == Auth::user()->id || $this->task->user_id == Auth::user()->id)
         <div class="container"  style="display: {{$comments_view ?  'block' : 'none' }};">

            <div class="comment_message">

                @if (session()->has('message'))

                    <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                        {{ session('message') }}

                        <script>
                            setTimeout(function (){
                                $('.comment_note_task').val('');
                            },1000)
                            $('.comment_note_task').val('');
                        </script>
                    </div>

                @endif

            </div>
            <div class="container" style="padding: 25px 25px 0;margin: auto;border: 1px solid #ddd;">
                <form wire:submit.prevent="comment(Object.fromEntries(new FormData($event.target)))">
                    <label>
                        {{__('Comment')}}
                    </label>
                    <textarea name="note" class="form-control comment_note_task"></textarea>

                    <select style="z-index: 9999999;display: none;position:absolute;border:1px solid gray;" class="comment_note_task_popup">
                        <option value="">{{__('Select')}}</option>
                        @foreach ($users as $user)
                            <option value="{{$user->username}}">{{$user->name.' '.$user->last_name}}</option>
                        @endforeach
                    </select>
                    <script>
                        $(function() {
                            $('.comment_note_task_popup').change(function (){
                                $('.comment_note_task').val($('.comment_note_task').val()+$('.comment_note_task_popup').val());

                                $('.comment_note_task_popup').hide();
                            })
                            $('.comment_note_task').bind('input propertychange',function(ev) {
                                var comment_note =$(this).val();
                                var lastChar = comment_note[comment_note.length - 1];
                                $('.comment_message').hide();
                                if(lastChar == '@'){
                                    $('.comment_note_task_popup').css({
                                        'left': ev.currentTarget.offsetLeft + 10 + 'px',
                                        'top': ev.currentTarget.offsetHeight + 10 + 'px',
                                    });
                                    $('.comment_note_task_popup').show();
                                }
                            });

                        });
                    </script>
                    <br>
                    <div class="modal-footer">
                        <a {!! $live_wire ?'': 'href="'.route('tasksList').'"' !!}  type="button" class="btn btn-dark m-1 editModalClose">{{__('Cancel')}}</a>
                        <button type="submit" class="comment_note_task_btn btn btn-success m-1">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
            <div class="container" style="height: 300px;overflow: auto;margin-top: 10px;">
                @foreach($comments as $comment)
                    <div class="card col-11" style="margin: 5px auto!important;">
                        <div class="card-header">
                            {{$comment->creator->creator_name . ' ' .$comment->creator->creator_last_name}}
                        </div>
                        <div class="card-body">
                            <p class="card-text"  style="white-space: pre-wrap; white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;">
                                {!! preg_replace(
                                    '#@(\w+)#',
                                    '<span class="badge bg-primary p-2">@$1</span>',
                                    $comment->note
                                ) !!}
                            </p>
                        </div>
                        <div class="card-footer text-muted">
                            {!! \Morilog\Jalali\CalendarUtils::strftime('l d F Y H:i:s', strtotime($comment->created_at))  !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
