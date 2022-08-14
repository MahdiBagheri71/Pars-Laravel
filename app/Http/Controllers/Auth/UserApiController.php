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
            'user_login' => 'required',
            'password' => 'required'
        ]);
        $user_login = request()->input('user_login');
        $fieldType = filter_var($user_login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($fieldType, \request('user_login'))->first();
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
