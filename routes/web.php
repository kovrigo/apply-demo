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

Route::get('lang/{locale}', 'LocaleController@setLocale');
Route::get('set-profile/{profile}', 'UserController@setProfile');
Route::get('/', 'UserController@home');

Route::view('/makelove/gantt', 'gantt');