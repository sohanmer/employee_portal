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
Route::resource('files','Eportalcontroller');

Auth::routes();

Route::get('/home', 'Eportalcontroller@index');
Route::get('/show', 'Eportalcontroller@show');
Route::post('/store', 'Eportalcontroller@store');
/*Route::get('/download',function(){
    $file = public_path();
    var_dump($file).die();
});*/

