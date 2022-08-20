<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditStatusTask extends Component
{
    public $task;

    public $task_id,$status;

    //is live wire request other file for load modal
    public $live_wire=false;

    //tasks status list
    public $tasks_status;

    /**
     * mount var
     */
    public function mount()
    {
        //Not task for me only permission "edit me task"
        if(!Auth::user()->can('edit status tasks')){
            return ;
        }

        //set var
        $this->task_id = $this->task->id;
        $this->status = $this->task->status;
        $this->tasks_status = TaskStatus::byValue();//get status task by value
    }

    /**
     * set rules for validation
     */
    protected $rules = [
        'status' => 'required|in:cancel,success,retarded,doing,planned',
    ];

    /**
     * set rules for validation
     */
    public function rules()
    {
        return [
            'status' => [
                'required',
                function ($attribute, $value, $fail) {
                    if(!key_exists($value,$this->tasks_status)){
                        $fail(__('validation.in'));
                    }
                },
            ]
        ];
    }

    /**
     * updated
     * @param $propertyName
     * @throws view
     */
    public function updated($propertyName)
    {
        //Not task for me only permission "edit me task"
        if(!Auth::user()->can('edit status tasks')){
            return ;
        }
        $this->validateOnly($propertyName);

        //find task for edit
        $task = Tasks::find($this->task_id);

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
