<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tasks;

class UserApiController extends Controller
{
    /**
     * login user & get token
     */
    public function login()
    {
        \request()->validate([
            'login' => 'required',
            'password' => 'required'
        ]);
        $login = request()->input('login');
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($fieldType, \request('login'))->first();
        if (! $user || ! Hash::check(\request('password'), $user->password)) {
            return __('The provided credentials are incorrect.');
        }
        return $user->createToken('token_base_name', ['user:view'])->plainTextToken;
    }

    /**
     * get info user
     */
    public function getUser()
    {
        $this->authorize('view', \request()->user());
        return \request()->user();
    }

    /**
     * logout user
     */
    public function logout()
    {
        \request()->user()->tokens()->delete();
        return response([], 204);
    }


    /**
     * get taks user
     */
    public function getTasks(){
        $this->authorize('view', \request()->user());
        $user = \request()->user();
        return response()->json(['tasks' =>Tasks::where('user_id',$user->id)->get()], 200);
    }
}
