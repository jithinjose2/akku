<?php

namespace Akku\Http\Controllers;

use Illuminate\Http\Request;

use Akku\Http\Requests;
use Illuminate\Support\Facades\Auth;

class RuleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('rules.index', [
            'modules' => $user->modules
        ]);
    }
}
