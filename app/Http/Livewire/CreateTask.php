<?php

namespace App\Http\Livewire;

use App\Models\Tasks;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateTask extends Component
{
    public $users;

    public $name,$note,$status,$date,$time,$user_id;
    /**
     * mount var
     */
    public function mount()
    {
        //NOT admin Not allow edit
        if(Auth::user()->is_admin != 1){
            return redirect()->route('tasksList');
        }
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
        'user_id' => 'required|integer|exists:users,id'
    ];

    /**
     * create task
     */
    public function submit()
    {

        //NOT admin Not allow edit
        if(Auth::user()->is_admin != 1){
            return redirect()->route('tasksList');
        }

        //validate
        $this->validate();

        $task = Tasks::create([
            'name' => $this->name,
            'note' => $this->note,
            'status' => $this->status,
            'date' => $this->date,
            'time' => $this->time,
            'user_id' => $this->user_id,
            'create_by_id' => Auth::user()->id
        ]);

        //message success update
        session()->flash('type', 'success');
        session()->flash('message',  __('Tasks created successfully'));
        return redirect()->route('tasksList');
    }
}
