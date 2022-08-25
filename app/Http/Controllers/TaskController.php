<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvents;
use App\Models\Tasks;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //not admin not allow
        if(!Auth::user()->canany(['add tasks','add me tasks'])){
            return redirect()->route('tasksList');
        }

        return view('dashboard.taskCreate',[
            'users' => User::all()//get list user for tasks user id & create user filter
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        return 'store';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tasks  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Tasks $task)
    {
        $user = Auth::user();
        $user->notify(new \App\Notifications\Tasks());
        $task->notify(new \App\Notifications\Tasks());
        $task->notifications()->get()->markAsRead();
        event(new NotificationEvents($user->id));
        dd($task->notifications()->get()->toArray());
        //
        return $task;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tasks  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Tasks $task)
    {
        //Not allow edit
        if(!Auth::user()->canany(['edit me task','edit all tasks','edit status tasks'])){
            return redirect()->route('tasksList');
        }

        //not allow all task edit
        if(!Auth::user()->can('edit all tasks')) {
            $task = $task->where('user_id', Auth::user()->id);
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tasks  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tasks $task)
    {
        //
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tasks  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tasks $task)
    {
        dd($task);
        //
        return 'destroy';
    }
}
