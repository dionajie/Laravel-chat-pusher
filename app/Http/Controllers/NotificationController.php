<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class NotificationController extends Controller
{
    public function getIndex()
    {
        return view('notification');
    }

    public function postNotify(Request $request)
    {
        $notifyText = e($request->input('notify_text'));

        $pusher = App::make('pusher');
	    $pusher->trigger( 'notifications',
	                      'new-notification', 
	                      array('text' => 'Pesan '.$notifyText.' telah ditambahkan!'));
	}
}
