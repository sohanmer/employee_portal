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
    return view('welcome');
});
Route::resource('files','ActionController');

Auth::routes();



Route::get('/home', 'ActionController@index');
Route::post('/store', 'ActionController@store');
Route::post('/search', 'ActionController@search');
Route::get('files/{fileId}','ActionController@show')->name('files.show');
Route::post('/update','ActionController@update');
Route::get('/admin','AdminController@admin');
Route::get('/delete','AdminController@deleteUser');
Route::get('/admindelete','AdminController@admindelete');
Route::get('/download', "ActionController@download");


