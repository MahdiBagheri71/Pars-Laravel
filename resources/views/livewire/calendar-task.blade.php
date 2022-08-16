<div class="card">
    <div class="card-header">
        <a class="nav-link float-end m-1" href="{{ route('tasksFullCalendar') }}">{{__('Calendar Tasks')}}</a>
        <!-- Refresh Button-->
        <a wire:click="refreshCalendar" type="button"  title="{{__("Refresh")}}" class="text-dark float-start m-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" class="bi bi-arrow-counterclockwise"><path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"></path> <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"></path></svg>
        </a>
    </div>
    <div class="card-body">

        <!-- Calendar-->
        <div id='calendar-container' wire:ignore>
            <div id='calendar'></div>
        </div>

        <!-- Modal Edit-->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            {{__("Edit")}}
                        </h5>
                    </div>
                    <div class="modal-body">
                        @if($modal_task)
                            @if(Auth::user()->canany(['edit me task','edit all tasks']))
                                {{--                        live wire edit taks--}}
                                @livewire('edit-task',['task'=>$modal_task,'users'=>$users,'live_wire'=>true])
                            @elseif(Auth::user()->can('edit status tasks'))
                                {{--                        live wire edit status taks--}}
                                @livewire('edit-status-task',['task'=>$modal_task,'live_wire'=>true])
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Create-->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            {{__("Task Create")}}
                        </h5>
                    </div>
                    <div class="modal-body">
                        @if(Auth::user()->canany(['add tasks','add me tasks']) && $modal_task_id)
                            @livewire('create-task',['users'=>$users,'live_wire'=>true,'date_time'=>$modal_task_id])
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@push('scripts')
    <script>

        document.addEventListener('livewire:load', function () {

            var option = {

                isJalaali : true,

                isRTL : true ,

                lang : 'fa',

                locale: 'fa',

                header: {
                    left: 'next,prev today myCustomButton',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },

                editable: false,

                displayEventTime: true,

                eventRender: function(eventObj, $el) {
                    $el.popover({
                        title: eventObj.title,
                        content: eventObj.description,
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body'
                    });
                },

                selectable: true,

                selectHelper: false ,

                select: function (start, end, allDay) {
                    let date = start.format('YYYY-MM-DD HH:mm:ss');
                    @this.showModal(date,'create')

                },
                eventClick: function (event) {
                    @this.showModal(event.id,'edit')
                }
            };

            $('#calendar').fullCalendar(option);

            $('#calendar').fullCalendar('addEventSource', function (start, end, timezone, callback) {

                var load_task = @this.loadTasks(start,end);
                Promise.all([load_task]).then((tasks) => {
                    // console.log(tasks[0]);
                    callback(tasks[0]);
                });

            });

            @this.on(`refreshCalendar`, () => {
                $('#calendar').fullCalendar('destroy');
                $('#calendar').fullCalendar(option);
                $('#calendar').fullCalendar('addEventSource', function (start, end, timezone, callback) {
                    var load_task = @this.loadTasks(start,end);
                    Promise.all([load_task]).then((tasks) => {
                        // console.log(tasks[0]);
                        callback(tasks[0]);
                    });

                });
            });

            //for create modal task
            @this.on('modal_create', () => {
                $('#createModal').modal('show');

                //flatpickr date select jalali
                flatpickr("#dateCreate", {
                    enableTime: false,
                    "locale": "fa" ,
                    noCalendar: false,
                    time_24hr: true,
                    dateFormat: "Y-m-d"
                });

                //flatpickr time select
                flatpickr("#timeCreate", {
                    enableTime: true,
                    "locale": "fa" ,
                    noCalendar: true,
                    time_24hr: true,
                    dateFormat: "H:i"
                });

                //close modal create
                $('.createModalClose').click(function (){
                    Livewire.emit('regeneratedCodes');
                    $('#createModal').modal('hide');
                })
            });

            //for edit modal task
            @this.on('modal_edit', () => {
                $('#editModal').modal('show');

                flatpickr(".dateEdit", {
                    enableTime: false,
                    "locale": "fa" ,
                    noCalendar: false,
                    time_24hr: true,
                    dateFormat: "Y-m-d"
                });

                flatpickr(".timeEdit", {
                    enableTime: true,
                    "locale": "fa" ,
                    noCalendar: true,
                    time_24hr: true,
                    dateFormat: "H:i"
                });

                $('.editModalClose').click(function (){
                    Livewire.emit('regeneratedCodes');
                    $('#editModal').modal('hide');
                })
            });

        });
    </script>
@endpush
