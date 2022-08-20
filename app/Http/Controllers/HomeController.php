<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return $this->rendar();
    }

    /**
     * Show the application dashboard.
     *
     * BY Mahdi
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        return $this->rendar();
    }

    /**
     * @return View
     */
    public function rendar(){

        $tasks = Tasks::select(DB::raw('count(id) as total, status'))->where('user_id',Auth::user()->id)->groupBy('status')->get()->keyBy('status')->all();
        $tasks_status = TaskStatus::byValue();//get status task by value
        $task_data = [];
        $total = 0;
        foreach ($tasks_status as $task_status => $task_info){
            $count = isset($tasks[$task_status])?$tasks[$task_status]['total']:0;
            $task_data[]= [
                'name' =>  __($task_info['label']),
                'y' =>  $count,
                'drilldown' => $task_status ,
                'color' => $task_info['color']
            ];
            $total += $count;
        }
        return view('dashboard.home',
            [
                'task_data' => $task_data ,
                'total' => $total
            ]
        );
    }


}
