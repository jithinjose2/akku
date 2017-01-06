<?php

namespace Akku\Http\Controllers;

use Akku\Models\Module;
use Akku\Models\Thing;
use Auth;
use Illuminate\Http\Request;

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

    public function validateModule(Request $request)
    {
        $this->validate($request, ['module_key' => 'required', 'module_pin' => 'required']);

        if ($data = Module::where('key', $request->get('module_key'))->where('pin',
            $request->get('module_pin'))->first()
        ) {
            $things = Thing::select('key', 'name')->where('module_id', $data->id)->where('type', 1)->get();
//            $thingsArray[] = ['key' => 'module_name', 'name' => $data->name];
//            $thingsArray [] = $things->toArray();

            return response()->json(['things' => $things, 'module_name' => $data->name]);
        } else {
            return response()->json('not found ', 404);
        }

    }

    public function updateModule(Request $request)
    {
        $this->validate($request, [
                'module_key' => 'required',
                'module_pin' => 'required'
            ]
        );
        if ($module = Module::where('key', $request->get('module_key'))->where('pin',
            $request->get('module_pin'))->first()
        ) {
            $things = Thing::select('id','key', 'name')->where('module_id', $module->id)->where('type', 1)->get();
            $validateThing = [];
            foreach ($things as $thing) {
                $validateThing[$thing->key] = 'required';
            }
            $this->validate($request, $validateThing);
            $module->user_id = \Auth::user()->id;
            $module->name = $request->get('module_name');
            $module->save();
            foreach ($things as $thing) {
                $t=Thing::find($thing->id);
                $t->name = $request->get($thing->key);
                $t->save();
            }

            return response()->json($things);
        } else {
            return response()->json('not found ', 404);
        }

    }
}
