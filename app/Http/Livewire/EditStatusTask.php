<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use Livewire\Component;

class EditStatusTask extends Component
{
    public $task;

    public $task_id,$status;

    /**
     * mount var
     */
    public function mount()
    {
        //set var
        $this->task_id = $this->task->id;
        $this->status = $this->task->status;
    }

    /**
     * set rules for validation
     */
    protected $rules = [
        'status' => 'required|in:cancel,success,retarded,doing,planned',
    ];

    /**
     * update task status
     */
    public function submit()
    {
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
        $task->status = $this->status;

        //update task
        $task->save();

        //set task var
        $this->task = $task;

        //message success update
        session()->flash('type', 'success');
        session()->flash('message',  __('Task status updated successfully'));
    }
}
