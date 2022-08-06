@extends('layouts.app')

@section('js_header')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/locale/fa.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

@endsection

@section('content')



  <!-- Modal -->
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

<div class="container">

    <h1 style="text-align: center">{{ __('Calendar Tasks') }}</h1>

    <div id='calendar'></div>

</div>



<script>

$(document).ready(function () {



    var SITEURL = "{{ url('/') }}";



    $.ajaxSetup({

        headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });


    $('#btn-save-task').click(function(){
        $.ajax({

            url: SITEURL + "/api/setStatusTasks/"+ $('#task-id').val(),

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
            }

        });
    });



    var calendar = $('#calendar').fullCalendar({

        locale: 'fa',

        header: {
            left: 'prev,next today myCustomButton',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },

        editable: false,

        events: SITEURL + "/api/getTasksMeDate?token={{Auth::user()->api_token}}&type=fullcalendar",

        displayEventTime: true,

        eventRender: function (event, element, view) {

            if (event.allDay === 'true') {

                event.allDay = true;

            } else {

                event.allDay = false;

            }

        },

        selectable: {{Auth::user()->is_admin == 1 ? 'true' : 'false' }},

        selectHelper: false,

        select: function (start, end, allDay) {

            var title = prompt('Event Title:');

            if (title) {

                var start = $.fullCalendar.formatDate(start, "Y-MM-DD");

                var end = $.fullCalendar.formatDate(end, "Y-MM-DD");

                $.ajax({

                    url: SITEURL + "/fullcalenderAjax",

                    data: {

                        title: title,

                        start: start,

                        end: end,

                        type: 'add'

                    },

                    type: "POST",

                    success: function (data) {

                        displayMessage("Event Created Successfully");



                        calendar.fullCalendar('renderEvent',

                            {

                                id: data.id,

                                title: title,

                                start: start,

                                end: end,

                                allDay: allDay

                            },true);



                        calendar.fullCalendar('unselect');

                    }

                });

            }

        },

        eventClick: function (event) {

            $.ajax({

                url: SITEURL + "/api/getTask/"+event.id,

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

                        // displayMessage("Event Created Successfully");

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
