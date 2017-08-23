<?php
use Illuminate\Support\Facades\Log;
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
Route::post('resetToken', function() {
    if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            return response(csrf_token());
    }
    
    throw new \Illuminate\Auth\AuthenticationException;
});

Route::get('/', function () {
    return view('welcome');
});

Route::delete('/profiles/{user}/notifications/{notificaton}', 'UserNotificationsController@destroy')->name('profile');
Route::get('/api/users', 'UserController@index');
Route::post('/api/users/{user}/avatar', 'UserAvatarController@store')->middleware('auth')->name('avatar');
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index')->name('profile');


Route::get('/threads/', 'ThreadsController@index');
Route::get('/threads/create', 'ThreadsController@create');
Route::post('/threads', 'ThreadsController@store');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::post('/threads/{channel}/{thread}/subscribe', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscribe', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::post('/threads/{channel}/{thread}/reply', 'RepliesController@store');//->middleware('throttle:15,1');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::post('/reply/{reply}/favorites', 'FavoritesController@store');
Route::delete('/reply/{reply}/favorites', 'FavoritesController@destroy');
Auth::routes();

Route::get('/home', 'HomeController@index');
