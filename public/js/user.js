$('#spinner_task').hide();
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
    $('.editModalClose').click(function (){
        Livewire.emit('regeneratedCodes');
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
        Livewire.emit('regeneratedCodes');
        $('#createModal').modal('hide');
    })
});

//for close Modal create task save event
window.livewire.on('closeModal', () => {
    Livewire.emit('regeneratedCodes');
    setTimeout(function (){
        $('.message-create-close').click();
        $('#createModal').modal('hide');
    },500);
    $('.resetCloseCreate').val('');
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
