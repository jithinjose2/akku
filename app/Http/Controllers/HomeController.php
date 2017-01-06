<?php

namespace Akku\Http\Controllers;

use Akku\Http\Requests;
use Illuminate\Http\Request;
use Akku\Repositories\RuleRepository;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RuleRepository $ruleRepo){
        $this->ruleRepo = $ruleRepo;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['humidValues'] = $this->ruleRepo->getHumidValues(6);
        $data['tempValues'] = $this->ruleRepo->getTempValues(5);
        $data['powerValues'] = $this->ruleRepo->getPowerValues(7);
        return view('home', $data);
    }
    /**
     * Show the configure dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function configure()
    {
        return view('configure');
    }


}
