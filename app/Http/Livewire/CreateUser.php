<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    //is live wire request other file for load modal
    public $live_wire;

    //user data
    public $user_data=[
        'name' => '',
        'last_name' => '',
        'email' => '',
        'username' => '',
        'password' => '',
        'is_admin' => 0 ,
        'role' => []
    ];

    //list roles for select
    public $roles;

    /**
     * mount
     */
    public function mount(){
        $this->roles = Role::all()->pluck('name');
    }

    /**
     * set rules for validation
     */
    public function rules()
    {
        return [
            'user_data.name' => ['required', 'string', 'max:255'],
            'user_data.last_name' => ['required', 'string', 'max:255'],
            'user_data.email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'user_data.username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'user_data.password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_data.is_admin' => ['boolean'],
            'user_data.role' => [
                function ($attribute, $value, $fail) {
                    foreach ($value as $role){
                        if(!$this->roles->contains($role)){
                            $fail(__('validation.roles',[
                                'attribute' => $attribute,
                                'role' => $role
                            ]));
                        }
                    }
                },
            ]
        ];
    }

    /**
     * create user
     */
    public function create()
    {

        //Not allow edit
        if(!Auth::user()->hasRole('admin')){
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return ;
        }

        //validate
        $data = $this->validate();


        //create user
        $user = User::create([
            'name' => $data['user_data']['name'],
            'email' => $data['user_data']['email'],
            'is_admin' => $data['user_data']['is_admin'],
            'last_name' => $data['user_data']['last_name'],
            'username' => $data['user_data']['username'],
            'api_token' =>  Str::random(25),
            'password' => Hash::make($data['user_data']['password']),
        ]);

        //check user create
        if($user){
            if(isset($data['user_data']['role']) && is_array($data['user_data']['role'])){
                $user->assignRole($data['user_data']['role']);
            }
            //message success update
            session()->flash('type', 'success');
            session()->flash('message',  __('Users created successfully'));
        }else{
            session()->flash('type', 'error');
            session()->flash('message',  __('The provided credentials are incorrect.'));
        }

        //live wire request
        if($this->live_wire){
            $this->user_data = [
                'name' => '',
                'last_name' => '',
                'email' => '',
                'username' => '',
                'password' => '',
                'is_admin' => 0
            ];
            $this->emit('closeModal');
            return ;
        }

        return redirect()->route('usersList');
    }
}
