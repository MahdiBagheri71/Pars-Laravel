<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateTask extends Component
{
    public $users;

    public $name,$note,$status,$date,$time,$user_id;

    //is live wire request other file for load modal
    public $live_wire;

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
        $this->date = '';
        $this->time = '';
    }

        /**
     * set rules for validation
     */
    protected $rules = [
        'name' => 'required|max:255|min:3',
        'note' => 'required',
        'status' => 'required|in:cancel,success,retarded,delete,doing,planned',
        'date' => 'required|date_format:Y-m-d',
        'time' => 'required|date_format:H:i',
        'user_id' => 'required|exists:users,id'
    ];

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

        $task = Tasks::create([
            'name' => $this->name,
            'note' => $this->note,
            'status' => $this->status,
            'date' => $this->date,
            'time' => $this->time,
            'user_id' => Auth::user()->canany(['add tasks'])?$this->user_id:Auth::user()->id,
            'create_by_id' => Auth::user()->id
        ]);

        //message success update
        session()->flash('type', 'success');
        session()->flash('message',  __('Tasks created successfully'));

        if($this->live_wire){
            $this->user_id = Auth::user()->id;
            $this->status = 'planned';
            $this->name = '';
            $this->note = '';
            $this->date = '';
            $this->time = '';
            $this->emit('closeModal');
            return ;
        }
        return redirect()->route('tasksList');
    }
}
