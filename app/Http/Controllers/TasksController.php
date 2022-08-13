<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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


    public function tasksList(){
        return view('dashboard.tasksList');
    }
}
