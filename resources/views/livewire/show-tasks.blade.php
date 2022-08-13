<div>
    <input wire:model="search_tasks" type="text" placeholder="Search tasks..."/>
    <div>

        @foreach ($tasks as $task)

            <div>
                {{$task->name}}
            </div>
        @endforeach



        {{ $tasks->links() }}

    </div>
</div>
