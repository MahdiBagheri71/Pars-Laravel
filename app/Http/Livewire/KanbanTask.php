<?php

namespace App\Http\Livewire;

use App\Models\NotificationUser;
use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class KanbanTask extends Component
{
    //for pagination Live wire
    use WithPagination;

    //template pagination
    protected $paginationTheme = 'bootstrap';

    //tasks status list
    public $tasks_status;

    /**
     * for show spinner
     */
    public function boot()
    {
        $this->emit("show_spinner_task");
    }

    /**
     * for hide spinner
     */
    public function dehydrate()
    {
        $this->emit("hide_spinner_task");
    }

    public function changeSorting($sorting){
        dd($sorting);
    }

    /**
     * updated
     * @param $propertyName
     * @throws view
     */
    public function changeStatus($task_id,$status)
    {

        //Not task for me only permission "edit me task"
        if(!Auth::user()->can('edit status tasks')){
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return ;
        }

        $validator = Validator::make(
            ['status'=>$status],
            [
                'status' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if(!key_exists($value,$this->tasks_status)){
                            $fail(__('validation.in'));
                        }
                    },
                ]
            ]
        );

        if ($validator->fails())
        {
            session()->flash('type', 'error');
            session()->flash('message',  __('validation.in',[
                'attribute' => 'status'
            ]));
        }

        //find task for edit
        $task = Tasks::find((int)$task_id);

        //not find !!!
        if(!$task) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Tasks not found'));
            return  ;
        }

        //for notification
        NotificationUser::createNotification(
            [
                'notification' => __('وضعیت از مقدار :status1 به :status2 تغییر یافت.',[
                    'status1' => __($this->tasks_status[$status]['label']),
                    'status2' => __($this->tasks_status[$status]['label'])
                ]),
                'link' => '/task/'.$task->id,
                'show' => 0,
                'user_id' => $task->user_id
            ]);

        //set var for edit
        $task->status = $status;

        //update task
        $task->save();

        //message success update
        session()->flash('type', 'success');
        session()->flash('message',  __('Task status updated successfully'));

    }

    public function render()
    {
        $tasks = Tasks::orderBy('sorting', 'DESC');

        $tasks->where('user_id', Auth::user()->id);

        //order by and paginate
        $tasks=$tasks->paginate(20);

        return view('livewire.kanban-task',
        [
            'tasks' => $tasks
        ]);
    }
}
