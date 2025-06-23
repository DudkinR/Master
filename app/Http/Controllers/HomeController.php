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
            return view('reports.index_boss');
        }
        if(Auth::user()->roles->first()->name== 'Master'){
            return view('reports.index_master');
        }
        if(Auth::user()->roles->first()->name== 'Brigadir'){
            return view('reports.index_brig');
        }
        if(Auth::user()->roles->first()->name== 'Worker'){
            return view('reports.index_guest');
        }
        if(Auth::user()->roles->first()->name== 'Admin'){
            return view('reports.index_admin');
        }
        if(Auth::user()->roles->first()->name== 'Engineer'){
            return view('reports.index_eng');
        }
        return view('home');

    }
}
