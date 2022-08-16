<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class CreateTask extends Component
{
    public $users;

    public $name,$note,$status,$date,$time,$user_id;

    //is live wire request other file for load modal
    public $live_wire;

    public $date_time;

    /**
     * mount var
     */
    public function mount()
    {
        //Not allow edit
        $this->user_id = Auth::user()->id;
        $this->status = 'planned';
        $this->name = '';
        $this->note = '';
        $this->date = \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->date_time?$this->date_time:time()));
        $this->time = $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i');
    }

    /**
     * set rules for validation
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|min:3',
            'note' => 'required',
            'status' => 'required|in:cancel,success,retarded,delete,doing,planned',
            'date' => [
//                'required:Y-m-d',
                function ($attribute, $value, $fail) {
                    $this->checkValidateJalali( $value, $fail);
                },
            ],
            'time' => 'required|date_format:H:i',
            'user_id' => 'required|exists:users,id'
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
    public function create()
    {

        //Not allow edit
        if(!Auth::user()->canany(['add tasks','add me tasks'])){
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return ;
        }

        //validate
        $this->validate();

        //date jalali
        $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y-m-d',$this->date)->format('Y-m-d');

        //create task
        $task = Tasks::create([
            'name' => $this->name,
            'note' => $this->note,
            'status' => $this->status,
            'date' => $date,
            'time' => $this->time,
            'user_id' => Auth::user()->canany(['add tasks'])?$this->user_id:Auth::user()->id,
            'create_by_id' => Auth::user()->id
        ]);

        //check task create
        if($task){
            //message success update
            session()->flash('type', 'success');
            session()->flash('message',  __('Tasks created successfully'));
        }else{
            session()->flash('type', 'error');
            session()->flash('message',  __('The provided credentials are incorrect.'));
        }

        //live wire request
        if($this->live_wire){
            $this->user_id = Auth::user()->id;
            $this->status = 'planned';
            $this->name = '';
            $this->note = '';
            $this->date = \Morilog\Jalali\CalendarUtils::strftime('Y-m-d', strtotime($this->date_time?$this->date_time:time()));
            $this->time = $this->date_time?date('H:i',strtotime($this->date_time)):date('H:i');
            $this->emit('closeModal');
            return ;
        }

        return redirect()->route('tasksList');
    }
}
