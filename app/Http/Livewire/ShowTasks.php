<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

use Livewire\WithPagination;
use App\Models\Tasks;


class ShowTasks extends Component
{
    //for pagination Live wire
    use WithPagination;

    //template pagination
    protected $paginationTheme = 'bootstrap';

    //for filter object
    public $search_tasks = [];

    //order by
    public $order_by ='id';
    public $order = 'asc';//desc

    //message alert
    public $message_type,$message='';

    public $users;
    public $errors_message=[];

    public function mount(){
        $this->users = User::all();//get list user for tasks user id & create user filter
    }

    /**
     * change order by & order click header table
     * @param $field_name
     */
    public function orderBy($field_name){
        $this->order_by = $field_name;
        $this->order = $this->order == 'asc' ? 'desc' :'asc';
    }

    /**
     * @param $task_id
     * delete task
     */
    public function delete($task_id){
        if(Auth::user()->can('delete tasks')){
            Tasks::where('id', $task_id)->delete();
            $this->message_type = 'success';
            $this->message =  __('Tasks deleted successfully');
        }else{
            $this->message_type = 'danger';
            $this->message =  __('Not Allow!!!');
        }
    }

    private function checkValidateJalali( $value, $fail){
        $date = explode('-',$value);
        if (count($date) != 3 && \Morilog\Jalali\CalendarUtils::checkDate($date[0], $date[1], $date[2], true)) {
            $fail(__('validation.date_format_jalali'));
        }
    }

    /**
     * render list tasks
     * @return view
     */
    public function render()
    {
        //validate
        $validator = Validator::make(
            $this->search_tasks,
            [
                'name' => 'max:255|min:1',
                'note' => '',
                'status' => 'in:cancel,success,retarded,delete,doing,planned',
                'date_start' => [
                    'date_format:Y-m-d',
                    'before_or_equal:date_end',
                    function ($attribute, $value, $fail) {
                        $this->checkValidateJalali( $value, $fail);
                    },
                ],
                'date_end' => [
                    'date_format:Y-m-d',
                    'before_or_equal:date_end',
                    function ($attribute, $value, $fail) {
                        $this->checkValidateJalali( $value, $fail);
                    },
                ],
                'time_start' => 'date_format:H:i:s|before_or_equal:time_end',
                'time_end' => 'date_format:H:i:s|after_or_equal:time_start',
                'user_id' => 'exists:users,id',
                'create_by' => 'integer|exists:users,id',
            ]
        );

        //select tasks join user
        $tasks = Tasks::with(['user:id,name as user_name,last_name as user_last_name','creator:id,name as creator_name,last_name as creator_last_name']);

        if ($validator->fails())
        {
            // The given data did not pass validation
            $this->errors_message = $validator->errors()->all();
        }else{
            //reset error message not fail validator
            $this->errors_message = [];
        }

        //check permit view all tasks
        if(!Auth::user()->can('view all tasks')){
            $tasks->where('tasks.user_id', Auth::user()->id);
        }

        //filter name
        if(isset($this->search_tasks['name']) && !$validator->errors()->has('name')){
            $tasks->where('tasks.name', 'like', '%'.$this->search_tasks['name'].'%');
        }

        //filter note
        if(isset($this->search_tasks['note']) && !$validator->errors()->has('note')){
            $tasks->where('tasks.note', 'like', '%'.$this->search_tasks['note'].'%');
        }

        //filter status
        if(isset($this->search_tasks['status']) && $this->search_tasks['status']  && !$validator->errors()->has('status')){
            $tasks->where('tasks.status', $this->search_tasks['status']);
        }

        //filter user_id
        if(isset($this->search_tasks['user_id']) && $this->search_tasks['user_id']  && !$validator->errors()->has('user_id')){
            $tasks->where('tasks.user_id', $this->search_tasks['user_id']);
        }

        //filter create by id
        if(isset($this->search_tasks['create_by']) && $this->search_tasks['create_by']  && !$validator->errors()->has('create_by')){
            $tasks->where('tasks.create_by_id', $this->search_tasks['create_by']);
        }

        //filter date start
        if(isset($this->search_tasks['date_start']) && $this->search_tasks['date_start'] && !$validator->errors()->has('date_start')){
            $tasks->where('tasks.date' , '>=', $this->search_tasks['date_start']);
        }

        //filter date end
        if(isset($this->search_tasks['date_end']) && $this->search_tasks['date_end'] && !$validator->errors()->has('date_end')){
            $tasks->where('tasks.date' , '<=', $this->search_tasks['date_end']);
        }

        //filter time start
        if(isset($this->search_tasks['time_start']) && $this->search_tasks['time_start'] && !$validator->errors()->has('time_start')){
            $tasks->where('tasks.time' , '>=', $this->search_tasks['time_start']);
        }

        //filter time end
        if(isset($this->search_tasks['time_end']) && $this->search_tasks['time_end'] && !$validator->errors()->has('time_end')){
            $tasks->where('tasks.time' , '<=', $this->search_tasks['time_end']);
        }

        //order by and paginate
        $tasks=$tasks->orderBy($this->order_by, $this->order)->paginate(10);

        return view('livewire.show-tasks', [
            'tasks' => $tasks//tasks
        ]);
    }
}
