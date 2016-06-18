<?php

namespace Akku\Http\Controllers;

use Akku\Http\Requests;
use Illuminate\Http\Request;
use Thing;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $water_level_thing = Thing::where('key', 'WATERLEVEL01')->first();
        $motor_switch_thing = Thing::where('key', 'MOTOR01')->first();
        return view('home', [
            'water_level_thing' => $water_level_thing,
            'motor_switch_thing' => $motor_switch_thing
        ]);
    }
}
