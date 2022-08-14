<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditTask extends Component
{
    public $task;

    public $users;

    public $task_id,$name,$note,$status,$date,$time,$user_id;

    /**
     * mount var
     */
    public function mount()
    {
        //NOT admin Not allow edit
        if(Auth::user()->is_admin != 1){
            return redirect()->route('tasksList');
        }

        //set var
        $this->task_id = $this->task->id;
        $this->name = $this->task->name;
        $this->note = $this->task->note;
        $this->status = $this->task->status;
        $this->date = $this->task->date;
        $this->time = $this->task->time;
        $this->user_id = $this->task->user_id;
    }

    /**
     * set rules for validation
     */
    protected $rules = [
        'name' => 'required|max:255|min:3',
        'note' => 'required',
        'status' => 'required|in:cancel,success,retarded,delete,doing,planned',
        'date' => 'required|date_format:Y-m-d',
        'time' => 'required|date_format:H:i:s',
        'user_id' => 'required|integer|exists:users,id'
    ];

    public function submit()
    {

        //NOT admin Not allow edit
        if(Auth::user()->is_admin != 1){
            return redirect()->route('tasksList');
        }

        //validate
        $this->validate();

        //find task for edit
        $task = Tasks::find($this->task_id );

        //not find !!!
        if(!$task) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Tasks not found'));
            return  ;
        }

        //set var for edit
        $task->name = $this->name;
        $task->note = $this->note;
        $task->status = $this->status;
        $task->date = $this->date;
        $task->time = $this->time;
        $task->user_id = $this->user_id;

        //update task
        $task->save();

        //set task var
        $this->task = $task;

        //message success update
        session()->flash('type', 'success');
        session()->flash('message',  __('Task updated successfully'));
    }
}
