<?php

namespace App\Http\Livewire;

use App\Models\ColumnsModel;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\ViewUserModel;
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
    public $search_tasks = ['date_start_end'=>'','date_start_start'=>'','date_finish_end'=>'','date_finish_start'=>''];

    //order by
    public $order_by ='id';
    public $order = 'asc';//desc

    //message alert
    public $message_type;

    //list users
    public $users;

    //errors message array
    public $errors_message=[];

    //modal parameter
    public $modal_task_id,$modal_task=false,$modal_task_create=false;

    //refresh listeners
    protected $listeners = ['regeneratedCodes' => 'refresh'];

    //show list deleted
    public $deleted;

    //tasks status list
    public $tasks_status;

    //columns task
    public $columns_task,$columns_list_task,$column_add;

    public function updatedColumnAdd($column_id)
    {
        ViewUserModel::updateOrInsert(
                ['column_id' => $column_id],
                ['sorting' => 0, 'user_id' => Auth::id()],
            );
        $this->columns_task = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())
            ->orderBy('sorting','ASC')->get()->keyBy('columns.field')->all();
        $this->emit('closeColumnModal');
        $this->message_type = 'success';
        session()->flash('message', __('Columns updated successfully'));
    }

    public function updateColumnSorting($columns_list){
        foreach ($columns_list as $column_id=>$sorting){
            ViewUserModel::updateOrInsert(
                ['column_id' => $column_id],
                ['sorting' => $sorting, 'user_id' => Auth::id()],
            );
        }
        $this->columns_task = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())
            ->orderBy('sorting','ASC')->get()->keyBy('columns.field')->all();
        $this->message_type = 'success';
        session()->flash('message', __('Columns updated successfully'));
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
    }

    /**
     * refresh after create & edit
     */
    public function refresh()
    {
        $this->modal_task = false;
        $this->modal_task_create = false;
        $this->columns_task = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())
            ->orderBy('sorting','ASC')->get()->keyBy('columns.field')->all();
    }

    /**
     * mount var
     */
    public function mount(){
        $this->users = User::all();//get list user for tasks user id & create user filter
        $this->tasks_status = TaskStatus::byValue();//get status task by value
        $this->columns_task = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())
            ->orderBy('sorting','ASC')->get()->keyBy('columns.field')->all();
        $this->columns_list_task = ColumnsModel::where('model',  'Task')->get();
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
            session()->flash('message', __('Tasks deleted successfully'));
        }else{
            $this->message_type = 'danger';
            session()->flash('message', __('Not Allow!!!'));
        }
    }

    /**
     * @param $column_id
     * delete Columns
     */
    public function deleteColumns($column_id){
        $count = ViewUserModel::with('columns')->whereHas(
            'columns', function($q){
            $q->where('columns_model.model',  'Task');
        }
        )->where('user_id',  Auth::id())->count();
        if($count>2){
            ViewUserModel::where('user_id',Auth::id())->where('id',$column_id)->delete();
            $this->columns_task = ViewUserModel::with('columns')->whereHas(
                'columns', function($q){
                $q->where('columns_model.model',  'Task');
            }
            )->where('user_id',  Auth::id())
                ->orderBy('sorting','ASC')
                ->get()->keyBy('columns.field')->all();
            $this->message_type = 'success';
            session()->flash('message', __('Columns deleted successfully'));
        }else{
            $this->message_type = 'danger';
            session()->flash('message', __('Not Allow!!!'));
        }
    }

    /**
     *
     * @param $deleted
     */
    public function setDeleted($deleted){
        if(Auth::user()->hasRole('admin')){
            $this->deleted = $deleted;
        }
    }

    /**
     * @param $task_id
     * restore task
     */
    public function restore($task_id){
        if(Auth::user()->hasRole('admin')){
            Tasks::withTrashed()->find($task_id)->restore();
            $this->message_type = 'success';
            session()->flash('message', __('Tasks restore successfully'));
        }else{
            $this->message_type = 'danger';
            session()->flash('message', __('Not Allow!!!'));
        }
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
     * set var modal
     * @param $task_id
     * @param $type
     */
    public function showModal($task_id,$type){
        $this->modal_task_id = $task_id;
        $this->modal_task = false;
        $this->modal_task_create = false;
        if($type == 'edit'){
            $task = Tasks::where('id',$task_id);
            $task = $task->first();
            $this->modal_task = $task;
        }elseif($type == 'edit_status'){
            $task = Tasks::where('id',$task_id);
            $task = $task->first();
            $this->modal_task = $task;
        }elseif($type == 'create'){
            $this->modal_task_create = true;
        }
        $this->emit('modal_'.$type);
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
                'status' => [
//                  'in:cancel,success,retarded,delete,doing,planned',
                    function ($attribute, $value, $fail) {
                        if(!key_exists($value,$this->tasks_status)){
                            $fail(__('validation.in'));
                        }
                    },
                ],
                'date_start_start' => [
                    'before_or_equal:date_start_end',
                    function ($attribute, $value, $fail) {
                        $this->checkValidateJalali( $value, $fail);
                    },
                ],
                'date_start_end' => [
                    function ($attribute, $value, $fail) {
                        $this->checkValidateJalali( $value, $fail);
                    },
                ],
                'date_finish_start' => [
//                    'date_format:Y-m-d',
                    'before_or_equal:date_finish_end',
                    function ($attribute, $value, $fail) {
                        $this->checkValidateJalali( $value, $fail);
                    },
                ],
                'date_finish_end' => [
                    function ($attribute, $value, $fail) {
                        $this->checkValidateJalali( $value, $fail);
                    },
                ],
                'time_start_start' => 'date_format:H:i|before_or_equal:time_start_end',
                'time_start_end' => 'date_format:H:i|after_or_equal:time_start_start',
                'time_finish_start' => 'date_format:H:i|before_or_equal:time_finish_end',
                'time_finish_end' => 'date_format:H:i|after_or_equal:time_finish_start',
                'user_id' => 'exists:users,id',
                'create_by_id' => 'exists:users,id',
            ]
        );

        //select tasks join user
        $tasks = Tasks::with(['user:id,name as user_name,last_name as user_last_name','creator:id,name as creator_name,last_name as creator_last_name']);


        if($this->deleted && Auth::user()->hasRole('admin')){
            $tasks = $tasks->onlyTrashed();
        }

        //validator fail
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
        if(isset($this->search_tasks['create_by_id']) && $this->search_tasks['create_by_id']  && !$validator->errors()->has('create_by_id')){
            $tasks->where('tasks.create_by_id', $this->search_tasks['create_by_id']);
        }

        //filter date start start
        if(isset($this->search_tasks['date_start_start']) && $this->search_tasks['date_start_start'] && !$validator->errors()->has('date_start_start')){
            $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $this->search_tasks['date_start_start'])->format('Y-m-d');
            $tasks->where('tasks.date_start' , '>=',  $date);
        }

        //filter date start end
        if(isset($this->search_tasks['date_start_end']) && $this->search_tasks['date_start_end'] && !$validator->errors()->has('date_start_end')){
            $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $this->search_tasks['date_start_end'])->format('Y-m-d');
            $tasks->where('tasks.date_start' , '<=', $date);
        }

        //filter date finish start
        if(isset($this->search_tasks['date_finish_start']) && $this->search_tasks['date_finish_start'] && !$validator->errors()->has('date_finish_start')){
            $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $this->search_tasks['date_finish_start'])->format('Y-m-d');
            $tasks->where('tasks.date_finish' , '>=',  $date);
        }

        //filter date finish end
        if(isset($this->search_tasks['date_finish_end']) && $this->search_tasks['date_finish_end'] && !$validator->errors()->has('date_finish_end')){
            $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d', $this->search_tasks['date_finish_end'])->format('Y-m-d');
            $tasks->where('tasks.date_finish' , '<=', $date);
        }

        //filter time start
        if(isset($this->search_tasks['time_start_start']) && $this->search_tasks['time_start_start'] && !$validator->errors()->has('time_start_start')){
            $tasks->where('tasks.time_start' , '>=', $this->search_tasks['time_start_start']);
        }

        //filter time end
        if(isset($this->search_tasks['time_start_end']) && $this->search_tasks['time_start_end'] && !$validator->errors()->has('time_start_end')){
            $tasks->where('tasks.time_start' , '<=', $this->search_tasks['time_start_end']);
        }

        //filter time finish start
        if(isset($this->search_tasks['time_finish_start']) && $this->search_tasks['time_finish_start'] && !$validator->errors()->has('time_finish_start')){
            $tasks->where('tasks.time_finish' , '>=', $this->search_tasks['time_finish_start']);
        }

        //filter time finish end
        if(isset($this->search_tasks['time_finish_end']) && $this->search_tasks['time_finish_end'] && !$validator->errors()->has('time_finish_end')){
            $tasks->where('tasks.time_finish' , '<=', $this->search_tasks['time_finish_end']);
        }

        //order by and paginate
        $tasks=$tasks->orderBy($this->order_by, $this->order)->paginate(10);

        return view('livewire.show-tasks', [
            'tasks' => $tasks//tasks
        ]);
    }
}
