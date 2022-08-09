@extends('layouts.app')

@section('js_header')

<link rel="stylesheet" href="{{ url('/') }}/{{ asset('css/fullcalendar.min.css') }}" />

<script src="{{ url('/') }}/{{ asset('js/jquery.min.js') }}"></script>

<script src="{{ url('/') }}/{{ asset('js/moment.min.js') }}"></script>

<script src="{{ url('/') }}/{{ asset('js/moment-jalaali.js') }}"></script>

<script src="{{ url('/') }}/{{ asset('js/fullcalendar.min.js') }}"></script>
<script src="{{ url('/') }}/{{ asset('js/fa.js') }}"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

@endsection

@section('content')


<div class="container">

    <h1 style="text-align: center">{{ __('Calendar Tasks') }}</h1>

    <div id='calendar'></div>

    <!-- Modal Edit Tasks-->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <h5 class="modal-title" id="taskModalLabel"></h5>
            </div>
            <div class="modal-body" style="text-align: center">
                <input type="hidden" id="task-id">
                <div>
                    {{__('Note')}} :
                    <pre id="note-modal"></pre>
                </div>
                <div>
                    {{__('Status')}} :
                    <select  class="form-select" aria-label="{{__('Status')}}" id="select-status" style="text-align: center;">
                        <option value="cancel" style="background : #f0077f;">{{__('cancel')}}</option>
                        <option value="success" style="background : #4cd548;">{{__('success')}}</option>
                        <option value="retarded" style="background : #eecd18;">{{__('retarded')}}</option>
                        <option value="doing" style="background : #2094fb;">{{__('doing')}}</option>
                        <option value="planned" style="background : #04a1bb;">{{__('planned')}}</option>
                        {!! Auth::user()->is_admin == 1 ?  '<option value="delete" style="background : #bf565b;">'.__('delete').'</option>' : '' !!}
                    </select>

                    <span id="status-modal"></span>

                </div>
                <div>
                    {{__('Date')}} :
                    <span id="date-modal"></span>
                </div>
                <div>
                    {{__('Time')}} :
                    <span id="time-modal"></span>
                </div>
                <div>
                    {{__('User')}} :
                    <span id="user_id-modal"></span>
                </div>
                <div>
                    {{__('Create By')}} :
                    <span id="create_by_id-modal"></span>
                </div>

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
            <button type="button" id="btn-save-task" class="btn btn-primary">{{__('Save')}}</button>
            </div>
        </div>
        </div>
    </div>

    @if(Auth::user()->is_admin == 1 )


        <link rel="stylesheet" href="{{ url('/') }}/{{ asset('css/persian-datepicker.min.css') }}" />

        <script src="{{ url('/') }}/{{ asset('js/persian-date.min.js') }}"></script>
        <script src="{{ url('/') }}/{{ asset('js/persian-datepicker.min.js') }}"></script>
        <!-- Modal Add Tasks-->
        <div class="modal fade" id="taskAddModal" tabindex="-1" aria-labelledby="taskAddModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="taskAddModalLabel">{{__('Add Task')}}</h5>
                </div>
                <div class="modal-body" style="text-align: center">
                    <div id="alertError"></div>
                    <div>
                        <label class="form-check-label" for="name-add-modal">{{__('Name')}} :</label>
                        <input type="text" class="form-control" id="name-add-modal" />
                    </div>
                    <div class="form-group">
                        <label class="form-check-label" for="note-add-modal">{{__('Note')}} :</label>
                        <textarea class="form-control" id="note-add-modal"></textarea>
                    </div>
                    <div>
                        <label class="form-check-label" for="select-add-status">{{__('Status')}} :</label>
                        <select  class="form-select" aria-label="{{__('Status')}}" id="select-add-status" style="text-align: center;">
                            <option value="cancel" style="background : #f0077f;">{{__('cancel')}}</option>
                            <option value="success" style="background : #4cd548;">{{__('success')}}</option>
                            <option value="retarded" style="background : #eecd18;">{{__('retarded')}}</option>
                            <option value="doing" style="background : #2094fb;">{{__('doing')}}</option>
                            <option value="planned" style="background : #04a1bb;" selected>{{__('planned')}}</option>
                            {!! Auth::user()->is_admin == 1 ?  '<option value="delete" style="background : #bf565b;">'.__('delete').'</option>' : '' !!}
                        </select>
                    </div>
                    <div>
                        <label class="form-check-label" for="date-add-modal">{{__('Date')}} :</label>
                        <input type="text" class="date-add-modal form-control"  />
                        <input id="date-add-modal" class="form-control" type="hidden">
                    </div>
                    <div>
                        <label class="form-check-label" for="time-add-modal">{{__('Time')}} :</label>
                        <input type="text" class="time-add-modal form-control" />
                        <input id="time-add-modal" class="form-control" type="hidden">
                    </div>
                    <div>
                        <label class="form-check-label" for="select-add-user">{{__('User')}} :</label>
                        <select  class="form-select" aria-label="{{__('User')}}" id="select-add-user" style="text-align: center;">
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                <button type="button" id="btn-save-add-task" class="btn btn-primary">{{__('Save')}}</button>
                </div>
            </div>
            </div>
        </div>
    @endif

</div>



<script>

$(document).ready(function () {


    var SITEURL = "{{ url('/') }}";



    $.ajaxSetup({

        headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });


    @if(Auth::user()->is_admin == 1 )
        var date_add_modal = $(".date-add-modal").pDatepicker(
            {
                initialValue: false,
                observer: true,
                format: 'YYYY-MM-DD',
                timePicker: {
                    enabled: false
                },
                altFieldFormatter : function(val){
                    var date  = new moment(val);
                    var date_format = date.format("YYYY-MM-DD");
                    $('#date-add-modal').val(date_format);
                    // console.log(date_format)
                }
        });

        $(".time-add-modal").pDatepicker(
            {
                onlyTimePicker: true,
                timePicker: {
                    meridian: {
                        enabled: true
                    },
                    second: {
                        enabled: true
                    }
                },
                altFieldFormatter : function(val){
                    var date  = new moment(val);
                    var time_format = date.format("HH:mm:ss") ;
                    $('#time-add-modal').val(time_format);
                    // console.log(date_format)
                },
                format: 'HH:mm:ss'
        });



        $('#btn-save-add-task').click(function(){
            $.ajax({

                url: SITEURL + "/api/addTasks",
                data: {
                    "token": '{{Auth::user()->api_token}}',
                    "name": $('#name-add-modal').val(),
                    "note":  $('#note-add-modal').val(),
                    "status":  $('#select-add-status').val(),
                    "date":  $('#date-add-modal').val(),
                    "time":  $('#time-add-modal').val(),
                    "user_id":  $('#select-add-user').val()
                },

                type: "POST",

                success: function (data) {
                    console.log(data);
                    $('#alertError').html("");
                    if(data && data.result && data.id){
                        $('#taskAddModal').modal('hide');
                        displayMessage(data.result);
                        window.location.reload()
                    }
                },

                error: function (result) {
                    var data = result.responseJSON;
                    if(data && data.errors){
                        $('#alertError').html("");
                        $.each( data.errors, function(  key,error ) {
                            var error_html = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>'+key+
                                ' : </strong>'+error.join("\n")+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                                $('#alertError').append(error_html);
                        });
                    }
                }

            });
        });


    @endif


    $('#btn-save-task').click(function(){
        $.ajax({

            url: SITEURL + "/api/{{ Auth::user()->is_admin == 1 ?'updateTasks':'setStatusTasks'}}/"+ $('#task-id').val(),

            data: {

                token: '{{Auth::user()->api_token}}',

                status :  $('#select-status').val()

            },

            type: "POST",

            success: function (data) {
                if(data && data.result && data.result.id){
                    console.log(data);
                    $('#taskModal').modal('hide');
                    displayMessage('{{ __("Task status updated successfully")}}');
                    window.location.reload()
                }
            },

            error: function (result) {
                var data = result.responseJSON;
                if(data && data.error){
                    $('#alertError').html("");

                    toastr.error(data.error, '{{__("Tasks")}}');
                }
            }

        });
    });



    var calendar = $('#calendar').fullCalendar({

        isJalaali : true,

        isRTL : true ,

        lang : 'fa',

        locale: 'fa',

        header: {
            left: 'prev,next today myCustomButton',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },

        editable: false,

        events: SITEURL + "/api/{{ Auth::user()->is_admin == 1 ?'getAllTasksDate' : 'getTasksMeDate'}}?token={{Auth::user()->api_token}}&type=fullcalendar",

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

        selectable: {{Auth::user()->is_admin == 1 ? 'true' : 'false' }},

        selectHelper: false,

        select: function (start, end, allDay) {

            $.ajax({

                url: SITEURL + "/api/getAllUser/",

                data: {

                    token: '{{Auth::user()->api_token}}',

                },

                type: "GET",

                success: function (data) {
                    var select_user = ''
                    if(data && data.users){
                        $.each( data.users, function(  key,user ) {
                            select_user = select_user+'<option value="'+user.id+'">'+user.name+' '+ user.last_name+'</option>';
                        });
                        date_add_modal.setDate(start.valueOf());

                        $('#taskAddModal').modal('show');

                    }
                    $('#select-add-user').html(select_user);
                }

            });

        },

        eventClick: function (event) {

            $.ajax({

                url: SITEURL + "/api/{{ Auth::user()->is_admin == 1 ?'getTaskAdmin' : 'getTask'}}/"+event.id,

                data: {

                    token: '{{Auth::user()->api_token}}',

                },

                type: "GET",

                success: function (data) {
                    if(data && data.id){
                        console.log(data);
                        $('#task-id').val(data.id)
                        $('#taskModalLabel').text(data.name)
                        $('#note-modal').text(data.note)
                        $('#date-modal').text(data.date)
                        $('#time-modal').text(data.time)
                        getuserinfo(SITEURL,data.user_id,'#user_id-modal');
                        getuserinfo(SITEURL,data.create_by_id,'#create_by_id-modal');
                        if(data.status == 'delete'){
                            $('#btn-save-task').hide();
                            $('#status-modal').show();
                            $('#select-status').hide();
                            $('#status-modal').text(data.status);
                        }else{
                            $('#status-modal').hide();
                            $('#btn-save-task').show();
                            $('#select-status').show();
                            $('#select-status').val(data.status);
                        }
                        $('#taskModal').modal('show');


                    }
                }

            });

        }



    });

});


function getuserinfo(url,id,elemnt_id){
    $.ajax({

        url: url + "/api/getUser/"+id,

        data: {

            token: '{{Auth::user()->api_token}}'

        },

        type: "GET",

        success: function (data) {
            if(data && data.id){
                console.log(data);
                $(elemnt_id).text( data.name + ' '+data. last_name);
            }

            return "";
        }

    });
}


function displayMessage(message) {

    toastr.success(message, '{{__("Tasks")}}');

}



</script>
@endsection
