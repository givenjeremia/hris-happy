<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

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
        // $ip = request()->ip();
        // // $ip = '103.226.226.86';
        // // dd($ip);
        // $data = Location::get($ip);  
        // dd($data);    
        return view('page.dashboard.index');
    }
}
