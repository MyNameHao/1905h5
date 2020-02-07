<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index.index');
});

Route::get('/user/regview','Index\UserController@regview');
Route::get('/user/loginview','Index\UserController@loginview');

Route::post('/user/reg','Index\UserController@reg');
Route::post('/user/login','Index\UserController@login');
Route::get('/user/personal','Index\UserController@personal');