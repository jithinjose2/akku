<?php

namespace Akku\Http\Controllers;

use Akku\Http\Requests;
use Illuminate\Http\Request;
use Auth;

class ModuleController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Auth::user()->modules;
        return view('modules', ['modules' => $modules]);
    }
}
