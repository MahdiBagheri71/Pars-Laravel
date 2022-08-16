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

//for create modal task
window.livewire.on('modal_create', () => {
    $('#createModal').modal('show');
    $('.createModalClose').click(function (){
        Livewire.emit('regeneratedCodes');
        $('#createModal').modal('hide');
    })
});

//for close Modal create task save event
window.livewire.on('closeModal', () => {
    Livewire.emit('regeneratedCodes');
    $('#createModal').modal('hide');
});

//for restore modal task
window.livewire.on('modal_restore', () => {
    $('#restoreModal').modal('show');
    $('.restoreModalClose').click(function (){
        $('#restoreModal').modal('hide');
    })
});
