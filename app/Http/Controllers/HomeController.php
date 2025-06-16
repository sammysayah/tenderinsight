<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Correctly import the Auth facade

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
        if (Auth::check()) {
            if (Auth::user()->usertype == 'admin') {
                return view('admin.dashboard'); // Admin dashboard
            } else {
                return view('user.dashboard'); // User dashboard
            }
        }

        return redirect('/login'); // Redirect to login if the user is not authenticated
    }
}
