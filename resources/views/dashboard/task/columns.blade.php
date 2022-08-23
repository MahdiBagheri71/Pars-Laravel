
<!-- Latest Sortable -->
<script src="http://SortableJS.github.io/Sortable/Sortable.js"></script>


<!-- Simple List -->
<div class="container">

    <div class="text-center p-1 m-2">
        <select wire:model="column_add" class="form-select custom-select text-center">
            <option value="">{{__('Select')}}</option>
            @foreach($columns_list_task as $column)
                @if(!array_key_exists($column['field'],$columns_task))
                    <option value="{{$column['id']}}">{{__($column['label'])}}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div id="column_list_select" class="list-group col">
        @foreach($columns_task as $column)
            <div class="list-group-item" style="" data-id="{{__($column['column_id'])}}" data-sorting="{{__($column['sorting'])}}">
                <svg type="button"  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrows-move handle" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708l2-2zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10zM.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L1.707 7.5H5.5a.5.5 0 0 1 0 1H1.707l1.147 1.146a.5.5 0 0 1-.708.708l-2-2zM10 8a.5.5 0 0 1 .5-.5h3.793l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L14.293 8.5H10.5A.5.5 0 0 1 10 8z"/>
                </svg>
                &nbsp;&nbsp;{{__($column['columns']['label'])}}
                <span type="button"  class="float-start">
                    <svg type="button" wire:click="deleteColumns('{{$column['id']}}')" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x deleteColumns" viewBox="0 0 16 16">
                      <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </span>
            </div>
        @endforeach
    </div>

</div>

<script>
    // Simple list
    Sortable.create(column_list_select, {
        handle: '.handle', // handle's class
        animation: 150
    });
    document.addEventListener('livewire:load', function () {
        // This WILL work because we are listening on the 'document',
        // for a click on an element with an ID of #test-element
        $(document).on("click","#columnModalSave",function() {
            var list_column = {};
            $.each($('#column_list_select>.list-group-item') ,function (key,el){
                list_column[$(el).attr('data-id')]=key;
            });
            @this.updateColumnSorting(list_column);
        });
    });
</script>
