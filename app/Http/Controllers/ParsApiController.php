<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Tasks;

class ParsApiController extends Controller
{
    public $user;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->user = User::where('api_token', $request->input('token','')) -> first();

    }

    /**
     * get info user login token api
     * return json
     */
    public function getMe(Request $request)
    {
        return response()->json(['me' => $this->user], 200);
    }


    /**
     * get all tasks api
     * return json
     */
    public function getAllTasks(Request $request)
    {
        return response()->json(['tasks' =>Tasks::all()], 200);
    }


    /**
     * get all tasks user api
     * return json
     */
    public function getAllTasksUser(Request $request,$user_id)
    {
        return response()->json(['tasks' =>Tasks::where('user_id',$user_id)->get()], 200);
    }
}
