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

Route::get('folder/{id}/item/{item}', 'FolderController@download');

Route::get('/', function () {
    return view('startpage');
});

Route::get('/home', function () {
    return view('startpage');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', 'Auth\LoginController@login');

Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('/admin', function () {
    return view('admin');
})->middleware('auth');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');
