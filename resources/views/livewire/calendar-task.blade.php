<div class="row justify-content-center">
    <div class="col-md-3 col-sm d-none d-sm-none d-md-block">
        <div class="card">
            <div class="card-header text-center p-2">
                {{__('Filter Status')}}
            </div>
            <div class="card-body text-center">
                @foreach($tasks_status as $key=>$status)
                    <div wire:click="toggleStatus('{{$key}}')" class="text-{{$key}} btn {{in_array($key,$tasks_status_filter)?'active':''}} col-md-12  col-12" style="margin-bottom: 10px;">
                        {{__($status['label'])}}
                    </div>
                @endforeach
            </div>
        </div>
        @if(Auth::user()->can('view all tasks'))
            <div class="card" style="margin-top: 15px;">
                <div class="card-header text-center p-2">
                    {{__('Filter Users')}}
                </div>
                <div class="card-body text-center">
                    @foreach($users as $user)
                        <div wire:click="toggleUsers('{{$user->id}}')" class="col-12 text-secondary fs-6 btn {{in_array($user->id,$tasks_user_id_filter)?'active':''}} col-md-12" style="margin-bottom: 10px;">
                            {{$user['name'].' '.$user['last_name']}}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="col-md-9 col-sm-12">
        <div class="card">
            <div id="spinner_task" class="spinner-border text-warning" role="status" style="position: fixed;left: 48%;z-index: 999999999;top: 48%;">
                <span class="visually-hidden">Loading...</span>
            </div>
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
                                {{__('Calendar Tasks')}}
                            </li>
                        </ol>
                    </nav>
                </div>
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
    </div>
</div>
@push('scripts')
    <script>


        document.addEventListener('livewire:load', function () {

            function regeneratedCodes(){
                $('#calendar').fullCalendar('addEventSource', function (start, end, timezone, callback) {
                    var load_task = @this.loadTasks(start,end);
                    Promise.all([load_task]).then((tasks) => {
                        $('#calendar').fullCalendar( 'removeEvents');
                        callback(tasks[0]);
                        $('#calendar').fullCalendar( 'rerenderEvents' );
                    });
                });
                $('.popover').hide();
            }

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
                regeneratedCodes();
            });

            //for create modal task
            @this.on('modal_create', () => {
                $('.popover').hide();
                $('#createModal').modal('show');

                //flatpickr date select jalali
                flatpickr("#dateCreate", {
                    enableTime: false,
                    "locale": "fa" ,
                    noCalendar: false,
                    time_24hr: true,
                    dateFormat: "Y-m-d",
                    static: true
                });

                //flatpickr time select
                flatpickr("#timeCreate", {
                    enableTime: true,
                    "locale": "fa" ,
                    noCalendar: true,
                    time_24hr: true,
                    dateFormat: "H:i",
                    static: true
                });

                //close modal create
                $('.createModalClose').click(function (){
                    $('#createModal').modal('hide');
                });

                $('#createModal').on('hidden.bs.modal', function () {
                    regeneratedCodes();
                });
            });

            //for edit modal task
            @this.on('modal_edit', () => {
                $('.popover').hide();
                $('#editModal').modal('show');

                flatpickr(".dateEdit", {
                    enableTime: false,
                    "locale": "fa" ,
                    noCalendar: false,
                    time_24hr: true,
                    dateFormat: "Y-m-d",
                    static: true
                });

                flatpickr(".timeEdit", {
                    enableTime: true,
                    "locale": "fa" ,
                    noCalendar: true,
                    time_24hr: true,
                    dateFormat: "H:i",
                    static: true
                });

                $('.editModalClose').click(function (){
                    $('#editModal').modal('hide');
                })

                $('#editModal').on('hidden.bs.modal', function () {
                    regeneratedCodes();
                });
            });


        });

        //for hide spinner task
        window.livewire.on('hide_spinner_task', () => {
            $('.popover').hide();
            setTimeout(function (){
                $('#spinner_task').hide();
            },200);
        });

        //for hide spinner task
        window.livewire.on('show_spinner_task', () => {
            $('.popover').hide();
            $('#show_spinner_task').show();
        });

        //for close Modal create task save event
        window.livewire.on('closeModal', () => {
            $('.popover').hide();
            setTimeout(function (){
                $('.message-create-close').click();
                $('#createModal').modal('hide');
            },500);
            $('.resetCloseCreate').val('');
        });
    </script>
@endpush
