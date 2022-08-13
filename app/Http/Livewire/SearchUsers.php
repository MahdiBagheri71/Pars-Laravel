<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class SearchUsers extends Component
{
    public $search = 'Ma';
    public $message = '';
    public $message2 = '';

    public function setMessageToHello(){
        $this->message = "HI Mahdi :)";
    }
    public function delete(){
        User::where('username','like', "%".$this->search."%")->delete();
    }

    public function render()

    {

        return view('livewire.search-users', [

            'users' => User::where('username','like', "%".$this->search."%")->get(),

        ])->layout('layouts.base');;

    }
}
