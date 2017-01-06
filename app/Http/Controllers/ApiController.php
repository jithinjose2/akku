<?php

namespace Akku\Http\Controllers;

use Akku\Models\Module;
use Akku\Models\Thing;

class ApiController extends Controller
{
    /*
     * getting only things
     * route /api/things
     * */
    public function getThings()
    {
        $module = Module::where('user_id', auth()->id())->first();
        return Thing::where('module_id', $module->id)->get();
    }

    /*
     * getting only module
     * route /api/module
     * */
    public function getModule()
    {
        return Module::where('user_id', auth()->id())->get();
    }

    /*
     * getting only module-things
     * route /api/module-things
     * */
    public function getModuleThings()
    {
        return Module::where('user_id', auth()->id())->with('things')->get();
    }
}
