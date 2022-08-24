<?php

namespace App\Http\Livewire;

use App\Models\NotificationUser;
use App\Models\Tasks;
use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateTask extends Component
{
    public $users;

    public $task_data = [
        'name' => '',
        'status' => '',
        'note' => '',
        'date' => '',
        'time' => '',
        'user_id' => ''
    ];

    //is live wire request other file for load modal
    public $live_wire;

    public $date_time;

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

    /**
     * mount var
     */
    public function mount()
    {
        $this->task_data = [
            'status' => 'planned',
            'name' => '',
            'note' => '',
            'date_start' => \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->date_time?$this->date_time:time())), // convert to jalali
            'date_finish' => \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->date_time?$this->date_time:time())), // convert to jalali
            'time_start' => $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i'),
            'time_finish' => $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i'),
            'user_id' => Auth::user()->id
        ];
        $this->tasks_status = TaskStatus::byValue();//get status task by value
    }

    /**
     * set rules for validation
     */
    public function rules()
    {
        return [
            'task_data.name' => 'required|max:255|min:3',
            'task_data.note' => 'required',
            'task_data.status' => [
                'required',
//                'in:cancel,success,retarded,delete,doing,planned',
                function ($attribute, $value, $fail) {
                    if(!key_exists($value,$this->tasks_status)){
                        $fail(__('validation.in'));
                    }
                },
            ],
            'task_data.date_start' => [
//                'required:Y-m-d',
                'before_or_equal:task_data.date_finish',
                function ($attribute, $value, $fail) {
                    $this->checkValidateJalali( $value, $fail);
                },
            ],
            'task_data.date_finish' => [
//                'required:Y-m-d',
                function ($attribute, $value, $fail) {
                    $this->checkValidateJalali( $value, $fail);
                },
            ],
            'task_data.time_start' => 'required|date_format:H:i',
            'task_data.time_finish' => 'required|date_format:H:i',
            'task_data.user_id' => 'required|exists:users,id'
        ];
    }

    /**
     * check validate jalali date
     * @param $value
     * @param $fail
     */
    private function checkValidateJalali( $value, $fail){
        $date = explode('-',$value);
        if (count($date) != 3) {
            $fail(__('validation.date_format_jalali'));
            return;
        }
        if (count($date) != 3 && \Morilog\Jalali\CalendarUtils::checkDate($date[0], $date[1], $date[2], true)) {
            $fail(__('validation.date_format_jalali'));
        }
    }

    /**
     * create task
     */
    public function create($task_data)
    {

        $this->task_data = $task_data;
        //Not allow edit
        if(!Auth::user()->canany(['add tasks','add me tasks'])){
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return ;
        }

        //validate
        $this->validate();

        //date jalali
        $date_start = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d',$this->task_data['date_start'])->format('Y-m-d');
        $date_finish = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d',$this->task_data['date_finish'])->format('Y-m-d');

        //create task
        $task = Tasks::create([
            'name' => $this->task_data['name'],
            'note' => $this->task_data['note'],
            'status' => $this->task_data['status'],
            'date_start' => $date_start,
            'time_start' => $this->task_data['time_start'],
            'date_finish' => $date_finish,
            'time_finish' => $this->task_data['time_finish'],
            'user_id' => Auth::user()->canany(['add tasks'])?$this->task_data['user_id']:Auth::user()->id,
            'create_by_id' => Auth::user()->id,
            'sorting' => Tasks::max('sorting'),
            'time_tracking' => 0
        ]);

        //check task create
        if($task){
            //for notification
            NotificationUser::createNotification(
                [
                    'notification' => __('وظیفه توسط کاربر :user ایجاد گردید.',[
                        'user' => Auth::user()->name.' '.Auth::user()->last_name
                    ]),
                    'link' => '/task/'.$task->id,
                    'show' => 0,
                    'user_id' => $task->user_id
                ]
            );
            //message success update
            session()->flash('type', 'success');
            session()->flash('message',  __('Tasks created successfully'));
        }else{
            session()->flash('type', 'error');
            session()->flash('message',  __('The provided credentials are incorrect.'));
        }

        //live wire request
        if($this->live_wire){
            $this->task_data = [
                'status' => 'planned',
                'name' => '',
                'note' => '',
                'date_start' => \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->date_time?$this->date_time:time())), // convert to jalali
                'date_finish' => \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->date_time?$this->date_time:time())), // convert to jalali
                'time_start' => $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i'),
                'time_finish' => $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i'),
                'time' => $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i'),
                'user_id' => Auth::user()->id
            ];
            $this->emit('closeModal');
            return ;
        }

        return redirect()->route('tasksList');
    }
}
