@switch($column['columns']['type'])

    @case('text')
        <span style="white-space: pre-wrap; white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;">
            {!! mb_substr($task[$column['columns']['field']],0,25).(mb_strlen($task[$column['columns']['field']])>25?' ...':'') !!}
        </span>
    @break

    @case('select')
        <span class="text-{{$task[$column['columns']['field']]}}" {!! (Auth::user()->can('edit status tasks') && !$deleted) ? ' type="button" wire:click="showModal('.$task->id.',\'edit_status\')"':''  !!}>
            {{__(isset($tasks_status[$task[$column['columns']['field']]])?$tasks_status[$task[$column['columns']['field']]]['label']:$task[$column['columns']['field']])}}
        </span>
    @break

    @case('time_tracking')
        @if(!$deleted)
            <span class="time_tracking">
                <span class="show_time_tracking_{{$task->id}}">
                    @if($task->time_tracking_start>0)
                        {{floor(($task[$column['columns']['field']]+(time()-$task->time_tracking_start))/60).'m '.(($task[$column['columns']['field']]+(time()-$task->time_tracking_start))%60).'s'}}
                    @else
                        {{floor($task[$column['columns']['field']]/60).'m '.($task[$column['columns']['field']]%60).'s'}}
                    @endif
                </span>
                <span id="show_time_tracking_click_{{$task->id}}" data-taskid="{{$task->id}}" data-time="{{$task->time_tracking_start>0?($task[$column['columns']['field']]+(time()-$task->time_tracking_start)):$task[$column['columns']['field']]}}" data-status="stop" type="button" class="action_time_tracking action_time_tracking_{{$task->id}}">
                    <svg style="color: #198754;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-circle-fill" viewBox="0 0 16 16">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>
                    </svg>
                </span>
                @if($task->time_tracking_start>0)
                    <script>
                        $(document).ready(function (){
                            setTimeout(function (){
                                if($('#show_time_tracking_click_{{$task->id}}').attr('data-status') == 'stop') {
                                    $('#show_time_tracking_click_{{$task->id}}').attr('data-time',Number($('#show_time_tracking_click_{{$task->id}}').attr('data-time'))+1);
                                    $('#show_time_tracking_click_{{$task->id}}').click();
                                }
                            },1000);
                        })
                    </script>
                @endif
            </span>
        @else
            {{floor($task->time_tracking/60).'m '.($task->time_tracking%60).'s'}}
        @endif
    @break

    @case('date')
        {!! \Morilog\Jalali\CalendarUtils::strftime('l d F Y', strtotime($task[$column['columns']['field']]))  !!}
    @break

    @case('related')
        @if($column['columns']['field'] == 'user_id')
            {{$task->user->user_name . ' ' .$task->user->user_last_name}}
        @elseif($column['columns']['field'] == 'create_by_id')
            {{$task->creator->creator_name . ' ' .$task->creator->creator_last_name}}
        @endif
    @break

    @default
        {{$task[$column['columns']['field']]}}

@endswitch
