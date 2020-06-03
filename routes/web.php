<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'UserController@home');
Route::get('/profile', 'UserController@profile');

Route::get('/login', 'UserController@showloginForm');
Route::post('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout');

Route::post('/check-email', 'UserController@checkEmail');
Route::post('/update-profile', 'UserController@updateProfile');

Route::get('/register', 'UserController@showRegistrationForm');
Route::post('/register', 'UserController@register');

Route::get('/logout', 'UserController@logout');

Route::resource('image', 'ImageController');