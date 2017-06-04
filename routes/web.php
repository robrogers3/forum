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


Route::get('/profiles/{user}', 'ProfilesController@show');

Route::get('/threads/', 'ThreadsController@index');
Route::get('/threads/create', 'ThreadsController@create');

Route::post('/threads', 'ThreadsController@store');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');

//Route::resource('threads', 'ThreadsController');

Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');
Route::patch('/reply/{reply}', 'RepliesController@update');
Route::delete('/reply/{reply}', 'RepliesController@destroy');
Route::post('/reply/{reply}/favorites', 'FavoritesController@store');
Route::delete('/reply/{reply}/favorites', 'FavoritesController@destroy');
Auth::routes();

Route::get('/home', 'HomeController@index');
