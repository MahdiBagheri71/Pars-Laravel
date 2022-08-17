<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ShowUsers extends Component
{
    //for pagination Live wire
    use WithPagination;

    //template pagination
    protected $paginationTheme = 'bootstrap';

    //for filter object
    public $search_users = [];

    //order by
    public $order_by ='id';
    public $order = 'asc';//desc

    //message alert
    public $message_type;

    //errors message array
    public $errors_message=[];

    //modal parameter
    public $modal_user_id,$modal_task=false;

    //refresh listeners
    protected $listeners = ['regeneratedCodes' => 'refresh'];

    //show list deleted
    public $deleted;

    //list roles for select
    public $roles;

    /**
     * mount
     */
    public function mount(){
        $this->roles = Role::all()->pluck('name');
    }

    /**
     * refresh after create & edit
     */
    public function refresh()
    {
        $this->modal_task = false;
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
     * @param $user_id
     * delete user
     */
    public function delete($user_id){
        User::where('id',$user_id)->delete();
        $this->message_type = 'success';
        session()->flash('message', __('Users deleted successfully'));
    }

    /**
     * @param $user_id
     * restore user
     */
    public function restore($user_id){
        User::withTrashed()->find($user_id)->restore();
        $this->message_type = 'success';
        session()->flash('message', __('Users restore successfully'));
    }

    /**
     * set var modal
     * @param $user_id
     * @param $type
     */
    public function showModal($user_id,$type){
        $this->modal_user_id = $user_id;
        $this->modal_task = false;
        if($type == 'edit'){
            $this->modal_task = true;
        }
        $this->emit('modal_'.$type);
    }

    public function render()
    {
        //validate
        $validator = Validator::make(
            $this->search_users,
            [
                'name' => 'min:1',
                'last_name' => 'min:1',
                'email' => 'min:1',
                'username' => 'min:1',
                'api_token' => 'min:1',
                'role' => [
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
            ]
        );

        //select users join user
        $users = User::orderBy($this->order_by, $this->order);

        //for show deleted record
        if($this->deleted){
            $users->onlyTrashed();
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

        //filter name
        if(isset($this->search_users['name']) && !$validator->errors()->has('name')){
            $this->resetPage();
            $users->where('users.name', 'like', '%'.$this->search_users['name'].'%');
        }

        //filter last_name
        if(isset($this->search_users['last_name']) && !$validator->errors()->has('last_name')){
            $this->resetPage();
            $users->where('users.last_name', 'like', '%'.$this->search_users['last_name'].'%');
        }

        //filter email
        if(isset($this->search_users['email']) && !$validator->errors()->has('email')){
            $this->resetPage();
            $users->where('users.email', 'like', '%'.$this->search_users['email'].'%');
        }

        //filter username
        if(isset($this->search_users['username']) && !$validator->errors()->has('username')){
            $this->resetPage();
            $users->where('users.username', 'like', '%'.$this->search_users['username'].'%');
        }

        //filter api_token
        if(isset($this->search_users['api_token']) && !$validator->errors()->has('api_token')){
            $this->resetPage();
            $users->where('users.api_token', 'like', '%'.$this->search_users['api_token'].'%');
        }

        //filter role
        if(isset($this->search_users['role']) && !$validator->errors()->has('role')){
            $this->resetPage();
            $users->whereHas(
                'roles', function($q){
                    $q->whereIn('roles.name',  $this->search_users['role']);
                }
            );
        }

        //order by and paginate
        $users=$users->paginate(10);

        return view('livewire.show-users',[
            'users' => $users
        ]);
    }
}
