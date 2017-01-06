<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('/test', function () {
    return view('test');
});

Route::auth();

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/configure', 'HomeController@configure');

Route::resource('/module', 'ModuleController');
Route::post('/validate-module', 'ModuleController@validateModule');
Route::post('/save-module', 'ModuleController@updateModule');

Route::get('/mirror', 'MirrorController@index');
Route::get('/proxy', 'MirrorController@proxy');
Route::get('/calendar', 'MirrorController@calendar');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::get('/rules', [
    'as' => 'rules.page',
    'uses' => 'RuleController@index'
]);


Route::match(['get', 'post'], '/rule-creation', [
    'as' => 'rule.add',
    'uses' => 'RuleController@addRule'
]);

Route::match(['post'], '/rule-deletion', [
    'as' => 'rule.delete',
    'uses' => 'RuleController@deleteRule'
]);
