<?php

namespace Akku\Http\Controllers;

use Akku\Http\Requests;
use Illuminate\Http\Request;
use Thing, Setting;

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
        $water_level_thing  = Thing::where('key', 'WATERLEVEL01')->first();
        $motor_switch_thing = Thing::where('key', 'MOTOR01')->first();
        $temprature_thing = Thing::where('key', 'TEMP01')->first();
        $humidity_thing = Thing::where('key', 'HUMID01')->first();
        $led_thing = Thing::where('key', 'LED01')->first();
        $led_switch     = Thing::where('key', 'SWITCHLED01')->first();
        $lcd_switch     = Thing::where('key', 'SWITCHLCD02')->first();
        $light_switch   = Thing::where('key', 'SWITCHLIGHT01')->first();

        $settings = Setting::all();
        $sets = [];
        foreach($settings as $setting){
            $sets[$setting->key] = $setting->value;
        }

        return view('home', [
            'water_level_thing'     => $water_level_thing,
            'motor_switch_thing'    => $motor_switch_thing,
            'temprature_thing'      => $temprature_thing,
            'humidity_thing'        => $humidity_thing,
            'led_thing'             => $led_thing,
            'led_switch'            => $led_switch,
            'lcd_switch'            => $lcd_switch,
            'light_switch'          => $light_switch,
            'settings'              => $sets
        ]);
    }
}
