<?php

namespace App\Http\Livewire;

use App\Models\TaskStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class ShowStatusTask extends Component
{
    //for pagination Live wire
    use WithPagination;

    //template pagination
    protected $paginationTheme = 'bootstrap';

    //for filter object
    public $search_status = [];

    //order by
    public $order_by ='id';
    public $order = 'asc';//desc

    //message alert
    public $message_type;

    //errors message array
    public $errors_message=[];

    //modal parameter
    public $modal_status_id,$modal_status=false,$modal_status_create=false;

    //refresh listeners
    protected $listeners = ['regeneratedCodes' => 'refresh'];

    //show list deleted
    public $deleted;


    /**
     * for show spinner
     */
    public function boot()
    {
        $this->emit("show_spinner_status");
    }

    /**
     * for hide spinner
     */
    public function dehydrate()
    {
        $this->emit("hide_spinner_status");
    }
    /**
     * refresh after create & edit
     */
    public function refresh()
    {
        $this->modal_status = false;
        $this->modal_status_create = false;

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
     * @param $status_id
     * delete status task
     */
    public function delete($status_id){
        if(Auth::user()->hasRole('admin')){
            TaskStatus::where('id', $status_id)->delete();
            $this->message_type = 'success';
            session()->flash('message', __('Status deleted successfully'));
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
     * @param $status_id
     * restore status task
     */
    public function restore($status_id){
        if(Auth::user()->hasRole('admin')){
            TaskStatus::withTrashed()->find($status_id)->restore();
            $this->message_type = 'success';
            session()->flash('message', __('Status restore successfully'));
        }else{
            $this->message_type = 'danger';
            session()->flash('message', __('Not Allow!!!'));
        }
    }

    /**
     * set var modal
     * @param $task_id
     * @param $type
     */
    public function showModal($status_id,$type){
        $this->modal_status_id = $status_id;
        $this->modal_status = false;
        $this->modal_status_create = false;
        if($type == 'edit'){
            $status = TaskStatus::where('id',$status_id);
            $status = $status->first();
            $this->modal_status = $status;
        }elseif($type == 'create'){
            $this->modal_status_create = true;
        }
        $this->emit('modal_'.$type);
    }

    /**
     * render list status tasks
     * @return view
     */
    public function render()
    {
        //validate
        $validator = Validator::make(
            $this->search_status,
            [
                'name' => 'max:255|min:1',
                'note' => '',
                'status' => 'in:cancel,success,retarded,delete,doing,planned',
                'date_start' => [
//                    'date_format:Y-m-d',
                    'before_or_equal:date_end',
                    function ($attribute, $value, $fail) {
                        $this->checkValidateJalali( $value, $fail);
                    },
                ],
                'date_end' => [
//                    'date_format:Y-m-d',
                    'before_or_equal:date_end',
                    function ($attribute, $value, $fail) {
                        $this->checkValidateJalali( $value, $fail);
                    },
                ],
                'time_start' => 'date_format:H:i|before_or_equal:time_end',
                'time_end' => 'date_format:H:i|after_or_equal:time_start',
                'user_id' => 'exists:users,id',
                'create_by' => 'exists:users,id',
            ]
        );

        //select tasks join user
        $tasks = TaskStatus::orderBy($this->order_by, $this->order);


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

        //filter value
        if(isset($this->search_status['value']) && !$validator->errors()->has('value')){
            $tasks->where('value', 'like', '%'.$this->search_status['value'].'%');
        }

        //filter label
        if(isset($this->search_status['label']) && !$validator->errors()->has('label')){
            $tasks->where('label', 'like', '%'.$this->search_status['label'].'%');
        }

        //filter color
        if(isset($this->search_status['color']) && !$validator->errors()->has('color')){
            $tasks->where('color', 'like', '%'.$this->search_status['color'].'%');
        }

        //order by and paginate
        $tasks=$tasks->paginate(10);

        return view('livewire.show-status-task', [
            'tasks' => $tasks//tasks
        ]);
    }
}
