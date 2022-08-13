<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Tasks;


class ShowTasks extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    //for filter object
    public $search_tasks = [];

    //order by
    public $order_by ='id';
    public $order = 'asc';//desc

    //icon sorting
    public $order_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-down-alt" viewBox="0 0 16 16">
  <path d="M3.5 3.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 12.293V3.5zm4 .5a.5.5 0 0 1 0-1h1a.5.5 0 0 1 0 1h-1zm0 3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1h-3zm0 3a.5.5 0 0 1 0-1h5a.5.5 0 0 1 0 1h-5zM7 12.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5z"/>
</svg>';//desc


    /**
     * change order by & order click header table
     * @param $field_name
     */
    public function orderBy($field_name){
        $this->order_by = $field_name;
        $this->order = $this->order == 'asc' ? 'desc' :'asc';
        $this->order_icon = $this->order == 'desc' ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-up" viewBox="0 0 16 16">
  <path d="M3.5 12.5a.5.5 0 0 1-1 0V3.707L1.354 4.854a.5.5 0 1 1-.708-.708l2-1.999.007-.007a.498.498 0 0 1 .7.006l2 2a.5.5 0 1 1-.707.708L3.5 3.707V12.5zm3.5-9a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zm0 3a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
</svg>' :'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sort-down-alt" viewBox="0 0 16 16">
  <path d="M3.5 3.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 12.293V3.5zm4 .5a.5.5 0 0 1 0-1h1a.5.5 0 0 1 0 1h-1zm0 3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1h-3zm0 3a.5.5 0 0 1 0-1h5a.5.5 0 0 1 0 1h-5zM7 12.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7a.5.5 0 0 0-.5.5z"/>
</svg>';
    }

    public function delete($task_id){
        Tasks::where('id', $task_id)->delete();
    }

    /**
     * render list tasks
     * @return view
     */
    public function render()
    {
        //select tasks join user
        $tasks = Tasks::join('users', 'users.id', '=', 'tasks.user_id')
            ->join('users as creator', 'creator.id', '=', 'tasks.create_by_id')
            ->select('tasks.*', 'users.name as user_name', 'users.last_name as user_last_name', 'creator.name as creator_name', 'creator.last_name as creator_last_name');


        //check admin user show all list tasks
        if(Auth::user()->is_admin != 1){
            $tasks->where('users.id', Auth::user()->id);
        }

        //filter name
        if(isset($this->search_tasks['name'])){
            $tasks->where('tasks.name', 'like', '%'.$this->search_tasks['name'].'%');
        }

        //filter note
        if(isset($this->search_tasks['note'])){
            $tasks->where('tasks.note', 'like', '%'.$this->search_tasks['note'].'%');
        }

        //filter status
        if(isset($this->search_tasks['status']) && $this->search_tasks['status']){
            $tasks->where('tasks.status', $this->search_tasks['status']);
        }

        //filter user_id
        if(isset($this->search_tasks['user_id']) && $this->search_tasks['user_id']){
            $tasks->where('tasks.user_id', $this->search_tasks['user_id']);
        }

        //filter create by id
        if(isset($this->search_tasks['create_by']) && $this->search_tasks['create_by']){
            $tasks->where('tasks.create_by_id', $this->search_tasks['create_by']);
        }

        //filter date start
        if(isset($this->search_tasks['date_start']) && $this->search_tasks['date_start']){
            $tasks->where('tasks.date' , '>=', $this->search_tasks['date_start']);
        }

        //filter date end
        if(isset($this->search_tasks['date_end']) && $this->search_tasks['date_end']){
            $tasks->where('tasks.date' , '<=', $this->search_tasks['date_end']);
        }

        //filter time start
        if(isset($this->search_tasks['time_start']) && $this->search_tasks['time_start']){
            $tasks->where('tasks.time' , '>=', $this->search_tasks['time_start']);
        }

        //filter time end
        if(isset($this->search_tasks['time_end']) && $this->search_tasks['time_end']){
            $tasks->where('tasks.time' , '<=', $this->search_tasks['time_end']);
        }

        //order by and paginate
        $tasks=$tasks->orderBy($this->order_by, $this->order)->paginate(10);

        return view('livewire.show-tasks', [
            'tasks' => $tasks,//tasks
            'users' => User::all()//get list user for tasks user id & create user filter
        ]);
    }
}
