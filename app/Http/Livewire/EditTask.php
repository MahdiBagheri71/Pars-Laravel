<?php

namespace App\Http\Livewire;

use App\Models\CommentsTask;
use App\Models\NotificationUser;
use App\Models\Tasks;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditTask extends Component
{
    public $task;

    public $users;

    //is live wire request other file for load modal
    public $live_wire = false;

    public $comments_view = false, $comments = [];

    public $task_data = [
        'name' => '',
        'status' => '',
        'note' => '',
        'date' => '',
        'time' => '',
        'user_id' => ''
    ];

    public $task_id;

    //tasks status list
    public $tasks_status;

    /**
     * mount var
     */
    public function mount()
    {
        //Not allow edit
        if (!Auth::user()->canany(['edit me task', 'edit all tasks'])) {
            return;
        }

        //Not task for me only permission "edit me task"
        if (!Auth::user()->can('edit all tasks') && $this->task->user_id != Auth::user()->id) {
            return;
        }

        //set var
        $this->task_id = $this->task->id;
        $this->task_data = [
            'status' => $this->task->status,
            'name' => $this->task->name,
            'note' => $this->task->note,
            'date' => \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->task->date)), // convert to jalali
            'time' => date('H:i', strtotime($this->task->time)),
            'user_id' => $this->task->user_id,
        ];
        $this->tasks_status = TaskStatus::byValue();//get status task by value


        $this->comments = CommentsTask::with(['creator:id,name as creator_name,last_name as creator_last_name'])->orderBy('created_at','DESC')->where('task_id', $this->task_id)->get();
    }

    /**
     * check validate jalali date
     * @param $value
     * @param $fail
     */
    private function checkValidateJalali($value, $fail)
    {
        $date = explode('-', $value);
        if (count($date) != 3) {
            $fail(__('validation.date_format_jalali'));
            return;
        }
        if (count($date) != 3 && \Morilog\Jalali\CalendarUtils::checkDate($date[0], $date[1], $date[2], true)) {
            $fail(__('validation.date_format_jalali'));
        }
    }

    public function setCommentsView($view)
    {
        $this->comments_view = $view;


        $this->comments = CommentsTask::with(['creator:id,name as creator_name,last_name as creator_last_name'])->orderBy('created_at','DESC')->where('task_id', $this->task_id)->get();
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
                    if (!key_exists($value, $this->tasks_status)) {
                        $fail(__('validation.in'));
                    }
                },
            ],
            'task_data.date' => [
                'required',
//                'date_format:Y-m-d',
                function ($attribute, $value, $fail) {
                    $this->checkValidateJalali($value, $fail);
                }
            ],
            'task_data.time' => 'required|date_format:H:i',
            'task_data.user_id' => 'required|integer|exists:users,id'
        ];
    }

    /**
     * update task
     */
    public function submit($task_data)
    {
        $this->task_data = $task_data;
        //Not allow edit
        if (!Auth::user()->canany(['edit me task', 'edit all tasks'])) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return;
        }

        //Not task for me only permission "edit me task"
        if (!Auth::user()->can('edit all tasks') && $this->task->user_id != Auth::user()->id) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return;
        }

        //validate
        $this->validate();

        //find task for edit
        $task = Tasks::find($this->task_id);

        //not find !!!
        if (!$task) {
            session()->flash('type', 'error');
            session()->flash('message', __('Tasks not found'));
            return;
        }

        //date jalali
        $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $this->task_data['date'])->format('Y-m-d');

        //set var for edit
        $task->name = $this->task_data['name'];
        $task->note = $this->task_data['note'];
        $task->status = $this->task_data['status'];
        $task->date = $date;
        $task->time = $this->task_data['time'];
        $task->user_id = $this->task_data['user_id'];

        //for notification
        NotificationUser::insert([
            [
                'notification' => __('وظیفه توسط کاربر :user تغییر یافت.', [
                    'user' => Auth::user()->name . ' ' . Auth::user()->last_name
                ]),
                'link' => '/task/' . $task->id,
                'show' => 0,
                'user_id' => $task->user_id
            ]
        ]);

        //update task
        $task->save();

        //set task var
        $this->task = $task;

        //message success update
        session()->flash('type', 'success');
        session()->flash('message', __('Task updated successfully'));
    }

    /**
     * @param $comment_data
     */
    public function comment($comment_data)
    {

        $this->comments = CommentsTask::with(['creator:id,name as creator_name,last_name as creator_last_name'])->orderBy('created_at','DESC')->where('task_id', $this->task_id)->get();

        //Not allow edit
        if (!Auth::user()->canany(['edit me task', 'edit all tasks'])) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return;
        }

        //Not task for me only permission "edit me task"
        if (!Auth::user()->can('edit all tasks') && $this->task->user_id != Auth::user()->id) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return;
        }

        //Not Allow
        if ($this->task->create_by_id != Auth::user()->id && $this->task->user_id != Auth::user()->id) {
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return;
        }

        $validator = Validator::make(
            $comment_data,
            [
                'note' => 'required'
            ]
        );

        if ($validator->fails())
        {
            session()->flash('type', 'error');
            session()->flash('message',  __('validation.required',[
                'attribute' => __('Comment')
            ]));
            return;
        }

        //insert comment
        $comment = CommentsTask::insert([
            [
                'note' => $comment_data['note'],
                'task_id' => $this->task_id,
                'create_by_id' => Auth::id(),
                'created_at' => date("Y-m-d H:i:s")
            ]
        ]);


        preg_match_all('#@(\w+)#', $comment_data['note'], $mentions);
        foreach ($mentions[1] as $user_name){

            $user = User::where('username',$user_name)->first();

            if($user){

                NotificationUser::insert([
                    [
                        'notification' => $comment_data['note'],
                        'link' => '/task/' . $this->task_id,
                        'show' => 0,
                        'user_id' => $user->id
                    ]
                ]);

            }
        }

        if($comment){

            $this->comments = CommentsTask::with(['creator:id,name as creator_name,last_name as creator_last_name'])->orderBy('created_at','DESC')->where('task_id', $this->task_id)->get();

            //message success comment
            session()->flash('type', 'success');
            session()->flash('message', __('Comment added successfully'));
        }
    }
}
