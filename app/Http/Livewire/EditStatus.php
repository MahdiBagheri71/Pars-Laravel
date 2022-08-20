<?php

namespace App\Http\Livewire;

use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditStatus extends Component
{
    public $task_status;

    //is live wire request other file for load modal
    public $live_wire=false;

    public $task_data_status = [
        'value' => '',
        'label' => '',
        'color' => ''
    ];

    public $task_status_id;

    /**
     * mount var
     */
    public function mount()
    {
        //Not allow edit
        if(!Auth::user()->canany(['edit me task','edit all tasks'])){
            return ;
        }

        //Not task for me only permission "edit me task"
        if(!Auth::user()->can('edit all tasks') && $this->task->user_id != Auth::user()->id){
            return ;
        }

        //set var
        $this->task_status_id = $this->task_status->id;
        $this->task_data_status = [
            'value' => $this->task_status->value,
            'label' => $this->task_status->label,
            'color' => $this->task_status->color
        ];
    }

    /**
     * set rules for validation
     */
    public function rules()
    {
        return [
            'task_data_status.value' => 'required|max:255|min:3',
            'task_data_status.label' => 'required|max:255|min:3',
            'task_data_status.color' => 'required|max:255|min:3',
        ];
    }

    /**
     * update task
     */
    public function submit($task_data_status)
    {
        $this->task_data_status = $task_data_status;
        //Not allow edit
        if(!Auth::user()->hasRole('admin')){
            return ;
        }

        //validate
        $this->validate();

        //find task status for edit
        $task_status = TaskStatus::find($this->task_status_id );

        //not find !!!
        if(!$task_status) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Tasks not found'));
            return  ;
        }
        //set var for edit
        $task_status->value = $this->task_data_status['value'];
        $task_status->label = $this->task_data_status['label'];
        $task_status->color = $this->task_data_status['color'];

        //update task
        $task_status->save();

        //set task var
        $this->task_data_status = $task_status;

        //message success update
        session()->flash('type', 'success');
        session()->flash('message',  __('Task updated successfully'));
    }
}
