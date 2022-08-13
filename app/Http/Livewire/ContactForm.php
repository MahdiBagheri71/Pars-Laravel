<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Contact;

class ContactForm extends Component
{
    public $name;

    public $email;

    public $last_name;

    public $message;



    protected $rules = [

        'name' => 'required|min:3',

        'email' => 'required|email',

    ];



    public function submit()

    {

        $this->validate();



        // Execution doesn't reach here if validation fails.



        Contact::create([

            'name' => $this->name,

            'email' => $this->email,

            'last_name' => $this->last_name,

        ]);

        $this->message = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>
با موفقیت ذخیره گردید
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';

        $this->name = '';
        $this->email = '';
        $this->last_name = '';

    }
}
