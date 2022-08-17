<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
     * show List Users
     * @return View
     */
    public function list(){
        //check permission view task
        if(Auth::user()->hasRole('admin')){
            return view('dashboard.user.list',[
                'delete' => false
            ]);
        }

        //not permission redirect
        return redirect()->route('dashboard');
    }

    /**
     * show List Users delete
     * @return View
     */
    public function listDelete(){
        //check permission view task
        if(Auth::user()->hasRole('admin')){
            return view('dashboard.user.list',[
                'delete' => true
            ]);
        }

        //not permission redirect
        return redirect()->route('dashboard');
    }

    /**
     * Edit Users profile
     * @return View
     */
    public function profile(){
        return view('dashboard.user.profile',[
            'user_id' => Auth::user()->id
        ]);
    }
}
