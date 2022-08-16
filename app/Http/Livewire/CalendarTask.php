<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CalendarTask extends Component
{

    //modal parameter
    public $modal_task_id,$modal_task=false;

    //list users
    public $users;

    //refresh listeners
    protected $listeners = ['regeneratedCodes' => 'refreshCalendar'];

    /**
     * mount var
     */
    public function mount(){
        $this->users = User::all();//get list user for tasks user id & create user filter
    }

    /**
     * refresh Calendar
     */
    public function refreshCalendar()
    {
        $this->modal_task_id = false;
        $this->modal_task = false;
        $this->emit('refreshCalendar');
    }

    /**
     * load task for calendar
     * @param $start
     * @param $end
     * @return array|void
     */
    public function loadTasks($start, $end)
    {
        //check allow
        if(!Auth::user()->canany(['view tasks', 'view all tasks'])){
            return ;
        }

        //color status
        $color = [
            "cancel" => '#f0077f',
            "success" => '#4cd548',
            "retarded" => "#eecd18",
            "delete" => "#bf565b",
            "doing" => "#2094fb",
            "planned" => "#04a1bb"
        ];

        //get tasks by date ( start & end )
        $tasks = Tasks::where('date','>=',date('Y-m-d',strtotime($start)))
            ->where('date','<=', date('Y-m-d',strtotime($end)))
            ->where('time','>=','00:00:00')
            ->where('time','<=','23:59:59');

        //not allow show all tasks
        if(!Auth::user()->can('view all tasks')){

            $tasks = $tasks->where('user_id',Auth::user()->id);
        }

        //get tasks
        $tasks = $tasks->get();

        //fullcalendar event dor show task
        $fullcaledar_task = [];

        foreach($tasks as $task){
            $fullcaledar_task[]=[
                'id' => $task->id,
                'title' => $task->name,
                'description' => $task->note,
                'start' => $task->date.' '.$task->time,
                'end' => $task->date.' '.$task->time,
                'color' => isset($color[$task->status])?$color[$task->status]:'#7b8a8c'
            ];
        }

        return $fullcaledar_task;
    }

    /**
     * set var modal
     * @param $task_id
     * @param $type
     */
    public function showModal($task_id,$type){
        $this->modal_task_id = $task_id;
        $this->modal_task = false;
        if($type == 'edit'){
            $task = Tasks::where('id',$task_id);
            $task = $task->first();
            $this->modal_task = $task;
        }
        $this->emit('modal_'.$type);
    }

    /**
     * rendaer
     * @return view
     */
    public function render()
    {
        return view('livewire.calendar-task');
    }
}
