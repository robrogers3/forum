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
use App\Thread;
Route::get('csv', function() {
    $f = fopen('php://memory', 'w+');
    $threads = Thread::get(['title', 'body', 'path']);
    $header = array_keys($threads->first()->toArray());
    fputcsv($f, $header);
    foreach ($threads as $thread)
        fputcsv($f, $thread->toArray());

    rewind($f);

    $headers = [
        'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="t.csv"',
    ];
    return \Response::make(stream_get_contents($f), 200, $headers);

});

Route::post('resetToken', function() {
    if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            return response(csrf_token());
    }

    throw new \Illuminate\Auth\AuthenticationException;
});

Route::get('/', function () {
    return view('welcome');
})->middleware('throttle:2,1');

Route::delete('/profiles/{user}/notifications/{notificaton}', 'UserNotificationsController@destroy')->name('profile');
Route::get('/api/users', 'UserController@index');
Route::get('/api/users/confirm-email-address', 'UserController@confirmAccount');
Route::post('/api/users/{user}/avatar', 'UserAvatarController@store')->middleware('auth')->name('avatar');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index')->name('profile');
Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');

Route::post('/channels', 'ChannelController@store')->middleware('auth');
Route::post('locked-threads/{thread}', 'LockThreadsController@store')->middleware('admin')->name('locked.threads.store');
Route::delete('locked-threads/{thread}', 'LockThreadsController@destroy')->middleware('admin')->name('locked.threads.destroy');
Route::get('/threads/', 'ThreadsController@index')->name('threads');
Route::get('/threads/create', 'ThreadsController@create');
Route::post('/threads', 'ThreadsController@store')->middleware('must-be-confirmed');
Route::get('/threads/{channel}', 'ThreadsController@index');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');
Route::patch('/threads/{channel}/{thread}', 'ThreadsController@update');
Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::post('/threads/{channel}/{thread}/subscribe', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscribe', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-reply.store');
Route::post('/replies/{reply}/unbest', 'BestRepliesController@unmark')->name('best-reply.unmark');

Route::post('/threads/{channel}/{thread}/reply', 'RepliesController@store')->middleware('auth');
Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');
Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::post('/reply/{reply}/favorites', 'FavoritesController@store');
Route::delete('/reply/{reply}/favorites', 'FavoritesController@destroy');
Auth::routes();

Route::get('/register/confirm', 'RegisterConfirmationController@index')->name('register.confirm');

Route::get('/home', 'HomeController@index');
