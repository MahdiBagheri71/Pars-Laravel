<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    public function list(){
        //check permission view task
        if(Auth::user()->canany(['view tasks', 'view all tasks'])){
            $tasks_status = TaskStatus::byValue();
            return view('dashboard.task.list',[
                'delete' => false,
                'tasks_status' => $tasks_status
            ]);
        }

        //not permission redirect
        return redirect()->route('dashboard');
    }

    /**
     * show List Tasks Delete
     * @return View
     */
    public function listDelete(){
        //check permission view delete task
        if(Auth::user()->hasRole('admin')){
            return view('dashboard.task.list',[
                'delete' => true
            ]);
        }

        //not permission redirect
        return redirect()->route('dashboard');
    }

    /**
     * @param $task_id
     * @return View
     */
    public function edit($task_id){
        //get task
        $task = Tasks::where('id',$task_id);

        //Not allow edit
        if(!Auth::user()->canany(['edit me task','edit all tasks','edit status tasks'])){
            return redirect()->route('tasksList');
        }

        //not allow all task edit
        if(!Auth::user()->can('edit all tasks')) {
            $task = $task->where('user_id', Auth::user()->id);
        }

        //get task in db
        $task = $task->first();

        //not exist task
        if(!$task){
            return redirect()->route('tasksList');
        }

        //not admin for not delete status
        if(!Auth::user()->can('view all tasks') && $task->status == 'delete') {
            return redirect()->route('tasksList');
        }

        return view('dashboard.task',[
            'task' => $task,
            'users' => User::all()//get list user for tasks user id & create user filter
        ]);
    }

    /**
     * @return view create task
     */
    public function create(){

        //not admin not allow
        if(!Auth::user()->canany(['add tasks','add me tasks'])){
            return redirect()->route('tasksList');
        }

        return view('dashboard.taskCreate',[
            'users' => User::all()//get list user for tasks user id & create user filter
        ]);
    }

    /**
     * show fullcalendar view tasks
     * @return view
     */
    public function tasksFullCalendar(){
        //check permission view task
        if(Auth::user()->canany(['view tasks', 'view all tasks'])){
            $tasks_status = TaskStatus::byValue();
            return view('dashboard.task.calendar',[
                'delete' => false,
                'tasks_status' => $tasks_status
            ]);
        }

        //not permission redirect
        return redirect()->route('dashboard');
    }

    /**
     * show fullcalendar view tasks
     * @return view
     */
    public function tasksKanban(){
        //check permission view task
        if(Auth::user()->canany(['view tasks', 'view all tasks'])){
            $tasks_status = TaskStatus::byValue();
            return view('dashboard.task.kanban',[
                'tasks_status' => $tasks_status
            ]);
        }

        //not permission redirect
        return redirect()->route('dashboard');
    }



    /**
     * show List Tasks
     * @return View
     */
    public function status(){
        //check permission view task
        if(Auth::user()->hasRole('admin')){
            $tasks_status = TaskStatus::byValue();
            return view('dashboard.task.status',[
                'delete' => false,
                'tasks_status' => $tasks_status
            ]);
        }

        //not permission redirect
        return redirect()->route('dashboard');
    }
}
