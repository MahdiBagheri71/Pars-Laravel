<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use App\Models\TaskStatus;
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

    //tasks status list
    public $tasks_status;

    //tasks status list for filter
    public $tasks_status_filter;

    //tasks users list for filter
    public $tasks_user_id_filter;

    /**
     * mount var
     */
    public function mount(){
        $this->users = User::all();//get list user for tasks user id & create user filter
        $this->tasks_status = TaskStatus::byValue();//get status task by value
        $this->tasks_status_filter = array_keys($this->tasks_status);//set status filter
        $this->tasks_user_id_filter = array_keys(User::byID());//set status filter
    }

    /**
     * toggle status filter
     * @param $status
     */
    public function toggleStatus($status){
        if(($key = array_search($status, $this->tasks_status_filter)) !== false){
            unset($this->tasks_status_filter[$key]);
        }else{
            $this->tasks_status_filter[] = $status;
        }
        $this->emit('refreshCalendar');
    }

    /**
     * toggle users filter
     * @param $status
     */
    public function toggleUsers($user_id){
        if(($key = array_search($user_id, $this->tasks_user_id_filter)) !== false){
            unset($this->tasks_user_id_filter[$key]);
        }else{
            $this->tasks_user_id_filter[] = $user_id;
        }
        $this->emit('refreshCalendar');
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
//        $this->setPage(1);
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

        $this->modal_task_id = false;
        $this->modal_task = false;

        //get tasks by date ( start & end )
        $tasks = Tasks::where('date','>=',date('Y-m-d',strtotime($start)))
            ->where('date','<=', date('Y-m-d',strtotime($end)))
            ->where('time','>=','00:00:00')
            ->where('time','<=','23:59:59');

        //not allow show all tasks
        if(!Auth::user()->can('view all tasks')){
            $tasks = $tasks->where('user_id',Auth::user()->id);
        }else{
            //filter user_id
            $tasks = $tasks->whereIn('user_id',$this->tasks_user_id_filter);
        }

        //filter status
        $tasks = $tasks->whereIn('status',$this->tasks_status_filter);

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
                'color' => isset($this->tasks_status[$task->status])?$this->tasks_status[$task->status]['color']:'#7b8a8c'
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
