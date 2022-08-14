<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use Livewire\Component;

class EditTask extends Component
{
    public $task;

    public $users;

    public $task_id,$name,$note,$status,$date,$time,$user_id;

    public function mount()
    {
        $this->task_id = $this->task->id;
        $this->name = $this->task->name;
        $this->note = $this->task->note;
        $this->status = $this->task->status;
        $this->date = $this->task->date;
        $this->time = $this->task->time;
        $this->user_id = $this->task->user_id;
    }

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
        $this->validate();
        $task = Tasks::find($this->task_id );


        if(!$task) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Tasks not found'));
            return  ;
        }

        $task->name = $this->name;
        $task->note = $this->note;
        $task->status = $this->status;
        $task->date = $this->date;
        $task->time = $this->time;
        $task->user_id = $this->user_id;

        $task->save();

        $this->task = $task;

        session()->flash('type', 'success');
        session()->flash('message',  __('Task updated successfully'));
    }
}
