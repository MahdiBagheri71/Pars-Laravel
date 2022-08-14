<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TasksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application tasksCalendar.
     *
     * BY Mahdi
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function tasksCalendar()
    {
        return view('dashboard.tasksCalendar');
    }

    /**
     * show List Tasks
     * @return View
     */
    public function tasksList(){
        return view('dashboard.tasksList');
    }

    /**
     * @param $task_id
     * @return View
     */
    public function taskShow($task_id){
        //get task
        $task = Tasks::where('id',$task_id)->first();

        //not exist task
        if(!$task){
            return redirect()->route('tasksList');
        }

        return view('dashboard.task',[
            'task' => $task,
            'users' => User::all()//get list user for tasks user id & create user filter
        ]);
    }
}
