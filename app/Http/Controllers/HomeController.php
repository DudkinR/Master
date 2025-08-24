<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;    

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
        // Attempt to read property "name" on null
        if(Auth::user()->roles->isEmpty()) {
            return view('home');
        }
        if(Auth::user()->roles->first()->name== 'Boss'){
            // Redirect to the route raports.index 
            return redirect()->route('raports.index');
        }
        if(Auth::user()->roles->first()->name== 'Master'){
            return redirect()->route('raports.index');
        }
        if(Auth::user()->roles->first()->name== 'Brigadir'){
            // якщо відкрита хоч якесь завдання та task.show 
            if(session('task_id')) {
                return redirect()->route('tasks.show', session('task_id'));
            }
            // якщо немає відкритого завдання то редірект на сторінку завдань            
            return redirect()->route('raports.index');
        }
        if(Auth::user()->roles->first()->name== 'Worker'){
            return view('raports.index_worker');
        }
        if(Auth::user()->roles->first()->name== 'Admin'){
            // Redirect to the route raports.index 
            return redirect()->route('raports.index');
        }
        if(Auth::user()->roles->first()->name== 'Engineer'){
            // Redirect to the route raports.index 
            return redirect()->route('raports.index');
        }
        return view('home');

    }
}
