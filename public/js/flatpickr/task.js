$('#spinner_task').hide();
var set_interval_time_tracking =[];
$(document).ready(function() {
    // This WILL work because we are listening on the 'document',
    // for a click on an element with an ID of #test-element
    $(document).on("click",".action_time_tracking",function() {
        var status = $(this).attr('data-status');
        var task_id_tracking = Number($(this).attr('data-taskid'));
        var time_tracking = Number($(this).attr('data-time'));
        var element = $(this);
        if(status == 'stop'){
            $(this).attr('data-status','play');
            $(this).html('<svg  style="color: #dc3545;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stop-circle-fill" viewBox="0 0 16 16">\n' +
                '  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.5 5A1.5 1.5 0 0 0 5 6.5v3A1.5 1.5 0 0 0 6.5 11h3A1.5 1.5 0 0 0 11 9.5v-3A1.5 1.5 0 0 0 9.5 5h-3z"/>\n' +
                '</svg>');
            set_interval_time_tracking[task_id_tracking]=setInterval(function (){
                time_tracking += 1;
                $('.action_time_tracking_'+task_id_tracking).attr('data-status','play').html('<svg style="color: #dc3545;"  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stop-circle-fill" viewBox="0 0 16 16">\n' +
                    '  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.5 5A1.5 1.5 0 0 0 5 6.5v3A1.5 1.5 0 0 0 6.5 11h3A1.5 1.5 0 0 0 11 9.5v-3A1.5 1.5 0 0 0 9.5 5h-3z"/>\n' +
                    '</svg>').attr('data-time',time_tracking);
                $('.show_time_tracking_'+task_id_tracking).text(Math.floor(time_tracking/60) +'m '+time_tracking%60 + 's');
                websocket.send('time_'+task_id_tracking+'_'+time_tracking);
            },1000)
        }else {
            $(this).attr('data-status','stop');
            $(this).html('<svg style="color: #198754;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-circle-fill" viewBox="0 0 16 16">\n' +
                '                                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814l-3.5-2.5z"/>\n' +
                '                                    </svg>');
            if(set_interval_time_tracking[task_id_tracking]){
                clearInterval(set_interval_time_tracking[task_id_tracking]);
            }
        }
    });
});
//after load
document.addEventListener('livewire:load', function () {
    //flatpickr date select jalali
    flatpickr("#startDate", {
        enableTime: false,
        "locale": "fa" ,
        noCalendar: false,
        time_24hr: true,
        dateFormat: "Y-m-d"
    });

    flatpickr("#endDate", {
        enableTime: false,
        "locale": "fa" ,
        noCalendar: false,
        time_24hr: true,
        dateFormat: "Y-m-d"
    });

    //flatpickr time select
    flatpickr("#startTime", {
        enableTime: true,
        "locale": "fa" ,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i"
    });

    flatpickr("#endTime", {
        enableTime: true,
        "locale": "fa" ,
        noCalendar: true,
        time_24hr: true,
        dateFormat: "H:i"
    });


});

//for delete modal task
window.livewire.on('modal_delete', () => {
    $('#deleteModal').modal('show');
    $('.deleteModalClose').click(function (){
        $('#deleteModal').modal('hide');
    })
});

//for edit status modal task
window.livewire.on('modal_edit_status', () => {
    $('#editStatusModal').modal('show');
    $('.editStatusModalClose').click(function (){
        $('#editStatusModal').modal('hide');
    });
    $('#editStatusModal').on('hidden.bs.modal', function () {
        Livewire.emit('regeneratedCodes');
    });
});

//for edit modal task
window.livewire.on('modal_edit', () => {
    $('#editModal').modal('show');
    $('#editModal').on('hidden.bs.modal', function () {
        Livewire.emit('regeneratedCodes');
    });

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
});

//for create modal task
window.livewire.on('modal_create', () => {
    $('#createModal').modal('show');

    $('#createModal').on('hidden.bs.modal', function () {
        Livewire.emit('regeneratedCodes');
    });
    $('.createModalClose').click(function (){
        $('.message-create-close').click();
        $('#createModal').modal('hide');
    })
});

//for close Modal create task save event
window.livewire.on('closeModal', () => {
    setTimeout(function (){
        $('.message-create-close').click();
        $('#createModal').modal('hide');
    },500);
    $('.resetCloseCreate').val('');
});

//for close Modal create task save event
window.livewire.on('closeColumnModal', () => {
    $('#columnModal').modal('hide');
});

//for restore modal task
window.livewire.on('modal_restore', () => {
    $('#restoreModal').modal('show');
    $('.restoreModalClose').click(function (){
        $('#restoreModal').modal('hide');
    })
});

//for column modal task
window.livewire.on('modal_column', () => {
    $('#columnModal').modal('show');
    $('.columnModalClose').click(function (){
        $('#columnModal').modal('hide');
    });
    $('.deleteColumns').click(function (){
        $('#columnModal').modal('hide');
    });
});

//for hide spinner task
window.livewire.on('hide_spinner_task', () => {
    setTimeout(function (){
        $('#spinner_task').hide();


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
    },200);
});

//for hide spinner task
window.livewire.on('show_spinner_task', () => {
    $('#show_spinner_task').show();
});
