$('#spinner_task').hide();
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

    flatpickr("#dateCreate", {
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

    flatpickr("#timeCreate", {
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
});

//for restore modal task
window.livewire.on('modal_restore', () => {
    $('#restoreModal').modal('show');
    $('.restoreModalClose').click(function (){
        $('#restoreModal').modal('hide');
    })
});

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
