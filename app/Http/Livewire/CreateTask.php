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
     * mount var
     */
    public function mount()
    {
        $this->task_data = [
            'status' => 'planned',
            'name' => '',
            'note' => '',
            'date' => \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->date_time?$this->date_time:time())), // convert to jalali
            'time' => $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i'),
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
            'task_data.date' => [
//                'required:Y-m-d',
                function ($attribute, $value, $fail) {
                    $this->checkValidateJalali( $value, $fail);
                },
            ],
            'task_data.time' => 'required|date_format:H:i',
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
        $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d',$this->task_data['date'])->format('Y-m-d');

        //create task
        $task = Tasks::create([
            'name' => $this->task_data['name'],
            'note' => $this->task_data['note'],
            'status' => $this->task_data['status'],
            'date' => $date,
            'time' => $this->task_data['time'],
            'user_id' => Auth::user()->canany(['add tasks'])?$this->task_data['user_id']:Auth::user()->id,
            'create_by_id' => Auth::user()->id
        ]);

        //check task create
        if($task){
            //for notification
            NotificationUser::insert([
                [
                    'notification' => __('وظیفه توسط کاربر :user ایجاد گردید.',[
                        'user' => Auth::user()->name.' '.Auth::user()->last_name
                    ]),
                    'link' => '/task/'.$task->id,
                    'show' => 0,
                    'user_id' => $task->user_id
                ]
            ]);
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
                'date' => \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->date_time?$this->date_time:time())), // convert to jalali
                'time' => $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i'),
                'user_id' => Auth::user()->id
            ];
            $this->emit('closeModal');
            return ;
        }

        return redirect()->route('tasksList');
    }
}
