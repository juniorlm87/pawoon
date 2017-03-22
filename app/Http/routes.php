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
    return view('users');
});
Route::get('/users', function () {
    return view('users');
});
Route::get('/users', function () {
    return view('users');
});
Route::get('userpro', 'UserproController@index');
Route::post('userpro/insert', 'UserproController@store');
Route::post('userpro/update', 'UserproController@update');
Route::get('userpro/show/{id}', 'UserproController@show');
Route::get('userpro/delete/{id}', 'UserproController@destroy');

