<?php
use Illuminate\Support\Facades\App;



Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');


// Auth
Route::Auth();
Route::get('/redirect/{provider}', 'SocialAuthController@redirect');
Route::get('/callback/{provider}', 'SocialAuthController@callback');


// test pusher
Route::get('/bridge', function() {
  	$pusher = App::make('pusher');
    $pusher->trigger( 'test-channel',
                      'test-event', 
                      array('text' => 'Preparing the Pusher Laracon.eu workshop!'));


    return view('welcome');
});

Route::controller('notifications', 'NotificationController');
Route::controller('chat', 'ChatController');



