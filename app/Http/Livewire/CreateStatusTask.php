<?php

namespace App\Http\Livewire;

use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateStatusTask extends Component
{

    //value task status value
    public $task_status_data = [
        'value' => '',
        'label' => '',
        'color' => ''
    ];

    //is live wire request other file for load modal
    public $live_wire;

    /**
     * set rules for validation
     */
    public function rules()
    {
        return [
            'task_status_data.value' => 'required|max:255|min:3',
            'task_status_data.label' => 'required|max:255|min:3',
            'task_status_data.color' => 'required|max:255|min:3',
        ];
    }

    /**
     * create task status
     */
    public function create($task_status_data)
    {

        $this->task_status_data = $task_status_data;
        //Not allow edit
        if(!Auth::user()->hasRole('admin')){
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return ;
        }

        //validate
        $this->validate();

        //create task status
        $task_status = TaskStatus::create([
            'value' => $this->task_status_data['value'],
            'label' => $this->task_status_data['label'],
            'color' => $this->task_status_data['color']
        ]);

        //check task status create
        if($task_status){
            //message success update
            session()->flash('type', 'success');
            session()->flash('message',  __('Tasks created successfully'));
        }else{
            session()->flash('type', 'error');
            session()->flash('message',  __('The provided credentials are incorrect.'));
        }

        //live wire request
        if($this->live_wire){
            $this->task_status_data = [
                'value' => '',
                'label' => '',
                'color' => ''
            ];
            $this->emit('closeModal');
        }
    }
}
