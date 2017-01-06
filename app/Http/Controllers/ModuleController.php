<?php

namespace Akku\Http\Controllers;

use Akku\Models\Module;
use Akku\Models\Thing;
use Akku\Models\User;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module = Module::find($id);
        $moduleUsers = $module->users->pluck('id')->toArray();
        $things = Thing::select('key', 'name', 'id')->where('module_id', $module->id)->where('type', 1)->get();
        $users = User::whereNotIn('id', [Auth::user()->id])->get();
        // dd($module);
        return view('module.edit')->withModule($module)
            ->withThings($things)
            ->withUsers($users)
            ->withModuleusers($moduleUsers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($module = Module::find($id)->first()
        ) {
            $things = Thing::select('id', 'key', 'name')->where('module_id', $module->id)->where('type', 1)->get();
            $validateThing = ['module_name' => 'required'];
            foreach ($things as $thing) {
                $validateThing[$thing->key] = 'required';
            }
            $this->validate($request, $validateThing);
            $module->user_id = \Auth::user()->id;
            $module->name = $request->get('module_name');
            $module->save();
            foreach ($things as $thing) {
                $t = Thing::find($thing->id);
                $t->name = $request->get($thing->key);
                $t->save();
            }
            $module->users()->sync($request->get('users', []));


            \Session::flash('message', 'Module Updated');
            \Session::flash('alert-class', 'alert-success');


            return redirect()->back();
        } else {
            return response()->json('not found ', 404);
        }
    }

    public function validateModule(Request $request)
    {
        $this->validate($request, ['module_key' => 'required', 'module_pin' => 'required']);

        if ($data = Module::where('key', $request->get('module_key'))
            ->where('pin', $request->get('module_pin'))
            ->whereNull('user_id')
            ->first()
        ) {
            $things = Thing::select('key', 'name')->where('module_id', $data->id)->where('type', 1)->get();
//            $thingsArray[] = ['key' => 'module_name', 'name' => $data->name];
//            $thingsArray [] = $things->toArray();

            return response()->json(['things' => $things, 'module_name' => $data->name]);
        } else {
            return response()->json(['result' => 'module already configured ! You are miss behaving '], 404);
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
            $things = Thing::select('id', 'key', 'name')->where('module_id', $module->id)->where('type', 1)->get();
            $validateThing = [];
            foreach ($things as $thing) {
                $validateThing[$thing->key] = 'required';
            }
            $this->validate($request, $validateThing);
            $module->user_id = \Auth::user()->id;
            $module->name = $request->get('module_name');
            $module->save();
            foreach ($things as $thing) {
                $t = Thing::find($thing->id);
                $t->name = $request->get($thing->key);
                $t->save();
            }

            return response()->json($things);
        } else {
            return response()->json('not found ', 404);
        }

    }
}
