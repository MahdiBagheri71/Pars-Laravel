<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Tasks;


class ShowTasks extends Component
{

    public $search_tasks = [
    ];

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();

    }



    public function delete($task_id){
        Tasks::where('id', $task_id)->delete();
    }

    public function render()
    {
        $tasks = Tasks::join('users', 'users.id', '=', 'tasks.user_id')
            ->join('users as creator', 'creator.id', '=', 'tasks.create_by_id')
            ->select('tasks.*', 'users.name as user_name', 'users.last_name as user_last_name', 'creator.name as creator_name', 'creator.last_name as creator_last_name');


        if(Auth::user()->is_admin != 1){
            $tasks->where('users.id', Auth::user()->id);
        }

        if(isset($this->search_tasks['name'])){
            $tasks->where('tasks.name', 'like', '%'.$this->search_tasks['name'].'%');
        }

        if(isset($this->search_tasks['note'])){
            $tasks->where('tasks.note', 'like', '%'.$this->search_tasks['note'].'%');
        }

        if(isset($this->search_tasks['status']) && $this->search_tasks['status']){
            $tasks->where('tasks.status', $this->search_tasks['status']);
        }

        if(isset($this->search_tasks['user_id']) && $this->search_tasks['user_id']){
            $tasks->where('tasks.user_id', $this->search_tasks['user_id']);
        }

        if(isset($this->search_tasks['create_by']) && $this->search_tasks['create_by']){
            $tasks->where('tasks.create_by_id', $this->search_tasks['create_by']);
        }

        if(isset($this->search_tasks['date_start']) && $this->search_tasks['date_start']){
            $tasks->where('tasks.date' , '>=', $this->search_tasks['date_start']);
        }

        if(isset($this->search_tasks['date_end']) && $this->search_tasks['date_end']){
            $tasks->where('tasks.date' , '<=', $this->search_tasks['date_end']);
        }

        if(isset($this->search_tasks['time_start']) && $this->search_tasks['time_start']){
            $tasks->where('tasks.time' , '>=', $this->search_tasks['time_start']);
        }

        if(isset($this->search_tasks['time_end']) && $this->search_tasks['time_end']){
            $tasks->where('tasks.time' , '<=', $this->search_tasks['time_end']);
        }

        $tasks=$tasks->paginate(10);
        return view('livewire.show-tasks', [

            'tasks' => $tasks,
            'users' => User::all()

        ]);
    }
}
