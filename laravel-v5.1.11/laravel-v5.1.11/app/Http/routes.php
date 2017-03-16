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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return redirect()->route('hi',['name'=>'xiaoming']);
});

Route::get('/hello/{name}', ['as'=>'hi', function ($name) {
    return 'hello '.$name;
}]);

Route::get('/user/add','TestController@add');
Route::post('/user/addHandle','TestController@addHandle');
Route::get('/user/list',['middleware'=>'login.check','uses'=>'TestController@listUser']);
Route::get('/user/update/{id}','TestController@update');
Route::post('/user/updateHandle','TestController@updateHandle');
Route::get('/user/del/{id}','TestController@del');

Route::get('/user/login','TestController@login');
Route::post('/user/dologin','TestController@doLogin');

