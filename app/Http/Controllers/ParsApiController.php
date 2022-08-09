<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Tasks;

class ParsApiController extends Controller
{
    /**
     * get info user login token api
     * return json
     */
    public function getMe(Request $request)
    {
        $user = User::where('api_token', $request->input('token','')) -> first();
        return response()->json(['me' => $user], 200);
    }


    /**
     * get tasks me api
     * return json
     */
    public function getTasksMe(Request $request)
    {
        $user = User::where('api_token', $request->input('token','')) -> first();
        return response()->json(['tasks' =>Tasks::where('user_id',$user->id)->get()], 200);
    }


    /**
     * get tasks id api
     * return json
     */
    public function getTask(Request $request ,$id)
    {
        $user = User::where('api_token', $request->input('token','')) -> first();
        return response()->json(Tasks::where('user_id',$user->id)->where('id',$id)->first(), 200);
    }


    /**
     * get tasks id api
     * return json
     */
    public function getTaskAdmin(Request $request ,$id)
    {
        $user = User::where('api_token', $request->input('token','')) -> first();
        return response()->json(Tasks::where('id',$id)->first(), 200);
    }


    /**
     * get tasks me by date api
     * return json
     */
    public function getTasksMeDate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'start' => 'required|date_format:Y-m-d',
                'end' => 'required|date_format:Y-m-d',
            ]
        );

        if ($validator->fails())
        {
            // The given data did not pass validation
            return response()->json(['errors' => $validator->messages() ], 403);
        }

        $user = User::where('api_token', $request->input('token','')) -> first();

        $tasks = Tasks::where('user_id',$user->id)
        ->where('date','>=',$request->input('start'))
        ->where('date','<=',$request->input('end'))
        ->where('time','>=','00:00:00')
        ->where('time','<=','23:59:59')
        ->get();

        $color = [
            "cancel" => '#f0077f',
            "success" => '#4cd548',
            "retarded" => "#eecd18",
            "delete" => "#bf565b",
            "doing" => "#2094fb",
            "planned" => "#04a1bb"
        ];


        if($request->type == 'fullcalendar'){
            $fullcaledar_task = array();
            foreach($tasks as $task){
                $fullcaledar_task[]=[
                    'id' => $task->id,
                    'title' => $task->name.' ('.$task->note.') ',
                    'start' => $task->date.' '.$task->time,
                    'end' => $task->date.' '.$task->time,
                    'color' => isset($color[$task->status])?$color[$task->status]:'#7b8a8c',

                ];
            }

            return response()->json($fullcaledar_task, 200);
        }

        return response()->json(['tasks' =>$tasks], 200);
    }




    /**
     * get all tasks by date api
     * return json
     */
    public function getAllTasksDate(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'start' => 'required|date_format:Y-m-d',
                'end' => 'required|date_format:Y-m-d',
            ]
        );

        if ($validator->fails())
        {
            // The given data did not pass validation
            return response()->json(['errors' => $validator->messages() ], 403);
        }

        $user = User::where('api_token', $request->input('token','')) -> first();

        $tasks = Tasks::where('date','>=',$request->input('start'))
        ->where('date','<=',$request->input('end'))
        ->where('time','>=','00:00:00')
        ->where('time','<=','23:59:59');

        if($request->type == 'fullcalendar'){
            $tasks = $tasks->join('users', 'tasks.user_id', '=', 'users.id')->select('tasks.*', 'users.name AS user_name', 'users.last_name AS user_last_name');
        }

        $tasks = $tasks->get();

        $color = [
            "cancel" => '#f0077f',
            "success" => '#4cd548',
            "retarded" => "#eecd18",
            "delete" => "#bf565b",
            "doing" => "#2094fb",
            "planned" => "#04a1bb"
        ];


        if($request->type == 'fullcalendar'){
            $fullcaledar_task = array();
            foreach($tasks as $task){
                $fullcaledar_task[]=[
                    'id' => $task->id,
                    'title' =>  $task->user_name.' '.$task->user_last_name .' : '.$task->name.' ('.$task->note.') ',
                    'start' => $task->date.' '.$task->time,
                    'end' => $task->date.' '.$task->time,
                    'color' => isset($color[$task->status])?$color[$task->status]:'#7b8a8c',
                ];
            }

            return response()->json($fullcaledar_task, 200);
        }

        return response()->json(['tasks' =>$tasks], 200);
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
     * get all tasks for user api
     * return json
     */
    public function getAllTasksUser(Request $request,$user_id)
    {
        return response()->json(['tasks' =>Tasks::where('user_id',$user_id)->get()], 200);
    }


    /**
     * get info user api
     * return json
     */
    public function getUser(Request $request,$user_id)
    {
        return response()->json(User::where('id',$user_id)->select('id', 'name', 'last_name')->first(), 200);
    }


    /**
     * get info  all users api
     * return json
     */
    public function getAllUser(Request $request)
    {
        return response()->json(['users' => User::select('id', 'name', 'last_name')->get()], 200);
    }
}
