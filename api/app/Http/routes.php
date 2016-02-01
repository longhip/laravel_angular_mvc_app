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
Route::group(['prefix'=>'api/v1'],function(){
	Route::post('upload','UploadController@postUpload');
	Route::controller('auth','Auth\AuthenticateController');
	Route::resource('user','User\UserController',['except' => ['create', 'edit']]);
	Route::controller('user','User\UserController');
});
