<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUser extends Component
{
    //is live wire request other file for load modal
    public $live_wire = false;

    public $user_data = [
        'name' => '',
        'last_name' => '',
        'email' => '',
        'username' => '',
        'password' => '',
        'password_confirmation' => '',
        'is_admin' => 0
    ];

    public $user_role;

    public $user_id;

    public $profile;

    //list roles for select
    public $roles;

    /**
     * mount var
     */
    public function mount()
    {
        //Not allow edit
        if (!Auth::user()->hasRole('admin')) {
            return;
        }

        //edit profile
        if($this->profile){
            $this->user_id = Auth::user()->id;
        }

        //get user
        if($this->user_id) {
            $user = User::where('id', $this->user_id)->first();

            if ($user) {
                //set var
                $this->user_data = [
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'is_admin' => $user->is_admin,
                    'password' => '',
                    'password_confirmation' => ''
                ];

                $this->user_role = $user->getRoleNames()->toArray();
            }
        }

        //get all roles
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
            'user_data.email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->user_id,'id'),
//                'unique:users,email'
            ],
            'user_data.username' => ['required', 'string', 'max:255',
                Rule::unique('users', 'username')->ignore($this->user_id,'id'),
//                'unique:users,username'
            ],
            'user_data.password' => [
//                'required',
                'string',
//                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if(strlen($value)>0){
                        if(strlen($value)<8){
                            $fail(__('validation.min.string',[
                                'attribute' => $attribute,
                                'min' => 8
                            ]));
                        }
                    }
                },
            ],
            'user_data.is_admin' => ['boolean'],
            'user_role' => [
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
     * update user
     */
    public function update($user_data)
    {
        $this->user_data = $user_data;

        //Not allow edit
        if(!Auth::user()->hasRole('admin')){
            session()->flash('type', 'error');
            session()->flash('message',  __('Not Allow!!!'));
            return ;
        }

        //validate
        $data = $this->validate();

        //edit profile
        if($this->profile){
            $this->user_id = Auth::user()->id;
        }

        //get user
        $user = User::where('id', $this->user_id)->first();

        if($user){
            //update user
            $user->name =$data['user_data']['name'];
            $user->email =$data['user_data']['email'];
//            $user->is_admin =$data['user_data']['is_admin'];
            $user->last_name =$data['user_data']['last_name'];
            $user->username =$data['user_data']['username'];

            //if set password
            if(strlen($data['user_data']['password'])>0){
                $user->password = Hash::make($data['user_data']['password']);
            }

            $user->save();

            if(isset($data['user_role']) && is_array($data['user_role']) && !$this->profile){
                $user->syncRoles($data['user_role']);
            }
            //message success update
            session()->flash('type', 'success');
            session()->flash('message',  __('Users updated successfully'));
        }else{
            session()->flash('type', 'error');
            session()->flash('message',  __('The provided credentials are incorrect.'));
        }
    }

}
