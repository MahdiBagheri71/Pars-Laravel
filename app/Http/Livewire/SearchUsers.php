<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class SearchUsers extends Component
{
    public $search = 'Ma';
    public $message = '';
    public $message2 = '';
    public $message3 = 'HI Mahdi message3 :)';



    protected $listeners = ['some-event' => 'refresh' ];

    public function setMessageToHello(){

        $this->dispatchBrowserEvent('name-updated', ['newName' => "OK setMessageToHello"]);
        $this->message = "HI Mahdi :)";
        $this->emit('some-event');
    }

    public function refresh(){

        $this->message2 = "HI Mahdi refresh :)";
    }

    public function delete(){
        User::where('username','like', "%".$this->search."%")->delete();
    }

    public function render()

    {
        $this->dispatchBrowserEvent('name-updated', ['newName' => "OK ME"]);
        return view('livewire.search-users', [

            'users' => User::where('username','like', "%".$this->search."%")->get(),

        ])->layout('layouts.base');;

    }

    public function getId(){
        return 123;
    }
}
