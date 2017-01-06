<?php

namespace Akku\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Akku\Http\Requests;
use Akku\Repositories\RuleRepository;
use Illuminate\Support\Facades\Auth;

class RuleController extends Controller
{
	public function __construct(RuleRepository $ruleRepo){
		$this->ruleRepo = $ruleRepo;
		$this->user = Auth::user();

	}
    public function index()
    {
    	if(!$this->user) {
    		return redirect('/');
    	}
        $this->ruleRepo = new RuleRepository();
        return view('rules.index', [
            'modules' => $this->user->modules,
            'rules' => $this->ruleRepo->getRulesList($this->user->id)
        ]);
    }

    public function addRule(Request $request){
    	if($request->isMethod('post')) {
    		$status = true;
	    	$rule_array = array();
	        foreach ($request->all() as $key => $value) {
	                $rule_array[$key] = stripslashes(trim($value));
	        }
	        DB::beginTransaction();
        	try {
	        $rule = $this->ruleRepo->createRule($rule_array, $this->user->id); 
	        $trigger = $this->ruleRepo->addTrigger($rule->id, $rule_array);
	        $action = $this->ruleRepo->addAction($rule->id, $rule_array);
	    	}
	    	catch (\Exception $e) {
	            $status = false;
	            DB::rollBack();
	            dd($e);
        	}
        	if ($status) {
        		DB::commit();
        	}
        	return redirect()->to(route('rules.page'));
    	} 
    	return redirect()->to(route('rules.page'));
    }

    public function deleteRule(Request $request){
    	if($request->isMethod('post')) {
    		$status = true;
	    	$rule_array = array();
	        foreach ($request->all() as $key => $value) {
	                $rule_array[$key] = stripslashes(trim($value));
	        }
	        DB::beginTransaction();
        	try {
	        	$rule = $this->ruleRepo->deleteRule($rule_array['rule_id']); 
	    	}
	    	catch (\Exception $e) {
	            $status = false;
	            DB::rollBack();
	            dd($e);
        	}
        	if ($status) {
        		DB::commit();
        	}
        	return redirect()->to(route('rules.page'));
    	} 
    	return redirect()->to(route('rules.page'));
    }
}
