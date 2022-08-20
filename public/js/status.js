$('#spinner_status').hide();

//for delete modal task
window.livewire.on('modal_delete', () => {
    $('#deleteModal').modal('show');
    $('.deleteModalClose').click(function (){
        $('#deleteModal').modal('hide');
    })
});

//for restore modal task
window.livewire.on('modal_restore', () => {
    $('#restoreModal').modal('show');
    $('.restoreModalClose').click(function (){
        $('#restoreModal').modal('hide');
    })
});


//for hide spinner task status
window.livewire.on('hide_spinner_status', () => {
    setTimeout(function (){
        $('#spinner_status').hide();
    },200);
});

//for hide spinner task status
window.livewire.on('show_spinner_status', () => {
    $('#show_spinner_status').show();
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


//for edit modal task
window.livewire.on('modal_edit', () => {
    $('#editModal').modal('show');
    $('#editModal').on('hidden.bs.modal', function () {
        Livewire.emit('regeneratedCodes');
    });

    $('.editModalClose').click(function (){
        $('#editModal').modal('hide');
    })
});
