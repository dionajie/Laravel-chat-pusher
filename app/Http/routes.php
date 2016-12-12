<?php
use Illuminate\Support\Facades\App;


Route::get('/', function () {
    return view('welcome');
});

Route::get('auth/github', 'Auth\AuthController@redirectToProvider');
Route::get('auth/github/callback', 'Auth\AuthController@handleProviderCallback');

Route::get('/bridge', function() {
  	$pusher = App::make('pusher');
    $pusher->trigger( 'test-channel',
                      'test-event', 
                      array('text' => 'Preparing the Pusher Laracon.eu workshop!'));


    return view('welcome');
});

Route::controller('notifications', 'NotificationController');
Route::controller('chat', 'ChatController');