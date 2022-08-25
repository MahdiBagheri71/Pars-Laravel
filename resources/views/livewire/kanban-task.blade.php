<div class="card">
    <div class="card-header">
        <div class="nav-link float-end m-1">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ __('Kanban Tasks') }}
                    </li>
                </ol>
            </nav>
        </div>
        <a id="refresh_tasks" wire:click="$emit('regeneratedCodes')" type="button"  title="{{__("Refresh")}}" class="text-dark float-start m-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-counterclockwise"><path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"></path> <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"></path></svg>
        </a>
    </div>
    <div class="card-body">

        @if (session()->has('message'))

            <div class="alert alert-{{session('type') == 'error' ? 'danger':'success'}}">

                {{ session('message') }}

            </div>

        @endif

        <div class="row flex-row flex-sm-nowrap py-3" style="overflow: auto;">
            <div id="spinner_task" class="spinner-border text-warning" role="status" style="position: fixed;left: 48%;z-index: 999999999;top: 48%;">
                <span class="visually-hidden">Loading...</span>
            </div>
            @foreach($tasks_status as $key=>$status)
                <div class="col-sm-2 col-md-2 col-xl-2 column_status">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 data-status="{{$key}}" style="background-color: #cccdce;border-radius: 6px;" class="card-title text-uppercase text-truncate py-2 text-center text-{{$key}} tasks_status">{{__($status['label'])}}</h6>

                            <div class="items border border-light text-center">
                                <div class="dropzone rounded" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="clearDrop(event)"> &nbsp; </div>
                            </div>

                            @foreach($tasks as $task)
                                @if($task->status == $key)
                                    <div class="items border border-light text-center">
                                        <div class="tasks_id card draggable shadow-sm" data-id="{{$task->id}}" data-sorting="{{$task->sorting}}" id="cd{{$task->id}}" draggable="true" ondragstart="drag(event)">
                                            <div class="tasks_name p-1 lead font-weight-light text-bg-{{$key}}">
                                                {{$task->name}}
                                            </div>
                                            <div class="card-body p-2">
                                                {!! mb_substr($task->note,0,30).(strlen($task->note)>30?' ...':'') !!}
                                            </div>
                                            <div class="p-1 lead font-weight-light text-bg-{{$key}}" style="font-size: 12px;direction: ltr;display: none;">
                                                {!! \Morilog\Jalali\CalendarUtils::strftime('Y-m-d H:i:s', strtotime($task->date_start.' '.$task->time_start))  !!}
                                                <br>
                                                {!! \Morilog\Jalali\CalendarUtils::strftime('Y-m-d H:i:s', strtotime($task->date_finish.' '.$task->time_finish))  !!}
                                            </div>
                                        </div>
                                        <div class="dropzone rounded" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="clearDrop(event)"> &nbsp; </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card-footer">
        {{--paginate--}}
        <div class="col-md-12">
            {{ $tasks->links() }}
        </div>
    </div>
</div>

@push('scripts')
    <script>
            const drag = (event) => {
                event.dataTransfer.setData("text/plain", event.target.id);
            }

            const allowDrop = (ev) => {
                ev.preventDefault();
                if (hasClass(ev.target, "dropzone")) {
                    addClass(ev.target, "droppable");
                }
            }

            const clearDrop = (ev) => {
                removeClass(ev.target, "droppable");
            }

            const drop = (event) => {
                event.preventDefault();
                const data = event.dataTransfer.getData("text/plain");
                const element = document.querySelector(`#${data}`);
                try {
                    // remove the spacer content from dropzone
                    event.target.removeChild(event.target.firstChild);
                    // add the draggable content
                    event.target.appendChild(element);
                    var task_id = $(element).attr('data-id');
                    var status = $(element).parents('.column_status').find('.tasks_status').attr('data-status');
                    $(element).find('.tasks_name').removeClass (function (index, className) {
                        return (className.match (/(^|\s)text-bg-\S+/g) || []).join(' ');
                    }).addClass('text-bg-'+status);
                    var task_list_sorting = [];
                    $.each($(element).parents('.column_status').find('.tasks_id'),function (key,el){
                        let t_id = Number($(el).attr('data-id'));
                        task_list_sorting[key] = t_id;
                    })
                    @this.changeStatus(task_id,status);
                    @this.changeSorting(task_list_sorting);
                    // console.log('status : '+status+' ==> task_id : '+task_id)
                    // console.log(task_list_sorting);
                    // remove the dropzone parent
                    unwrap(event.target);
                } catch (error) {
                    console.warn("can't move the item to the same place")
                }
                updateDropzones();
            }

            const updateDropzones = () => {
                /* after dropping, refresh the drop target areas
                  so there is a dropzone after each item
                  using jQuery here for simplicity */

                var dz = $('<div class="dropzone rounded" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="clearDrop(event)"> &nbsp; </div>');

                // delete old dropzones
                $('.dropzone').remove();

                // insert new dropdzone after each item
                dz.insertAfter('.card.draggable');

                // insert new dropzone in any empty swimlanes
                $(".items:not(:has(.card.draggable))").append(dz);
            };

            // helpers
            function hasClass(target, className) {
                return new RegExp('(\\s|^)' + className + '(\\s|$)').test(target.className);
            }

            function addClass(ele, cls) {
                if (!hasClass(ele, cls)) ele.className += " " + cls;
            }

            function removeClass(ele, cls) {
                if (hasClass(ele, cls)) {
                    var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
                    ele.className = ele.className.replace(reg, ' ');
                }
            }

            function unwrap(node) {
                node.replaceWith(...node.childNodes);
            }

        $('#spinner_task').hide();
        //for hide spinner task
        window.livewire.on('hide_spinner_task', () => {
            setTimeout(function (){
                $('#spinner_task').hide();
            },200);
        });

        //for hide spinner task
        window.livewire.on('show_spinner_task', () => {
            $('#show_spinner_task').show();
        });
    </script>
@endpush
