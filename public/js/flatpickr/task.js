
document.addEventListener('livewire:load', function () {

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

window.livewire.on('modal_delete', () => {
    $('#deleteModal').modal('show');
    $('.deleteModalClose').click(function (){
        $('#deleteModal').modal('hide');
    })
});

window.livewire.on('modal_edit', () => {
    $('#editModal').modal('show');
    $('.editModalClose').click(function (){
        Livewire.emit('regeneratedCodes');
        $('#editModal').modal('hide');
    })
});

window.livewire.on('modal_create', () => {
    $('#createModal').modal('show');
    $('.createModalClose').click(function (){
        Livewire.emit('regeneratedCodes');
        $('#createModal').modal('hide');
    })
});

window.livewire.on('closeModal', () => {
    Livewire.emit('regeneratedCodes');
    $('#createModal').modal('hide');
});
