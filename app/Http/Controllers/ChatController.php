<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Auth;
use App\History;
use DB;
use Carbon\Carbon;
// use Response;

class ChatController extends Controller
{
    var $pusher;
    var $user;
    var $chatChannel;

    const DEFAULT_CHAT_CHANNEL = 'chat';

    public function __construct()
    {
        $this->pusher = App::make('pusher');
        $this->user = Auth::user();
        $this->chatChannel = self::DEFAULT_CHAT_CHANNEL;
    }

    public function getIndex()
    {

        if(!$this->user)
        {
            return redirect('home');
        }
        // dd(time());
        $chatChannel = $this->chatChannel;

        return view('chat.chat', compact('chatChannel'));
    }

    public function postMessage(Request $request)
    {

        $message = [
            'text' => e($request->input('chat_text')),
            'username' => $this->user['name'],
            'avatar' => $this->user['avatar'],
            'timestamp' => Carbon::now()
        ];

        DB::table('history')->insert($message); 
        $this->pusher->trigger($this->chatChannel, 'new-message', $message);

    }

    public function getMessage() 
    {
        $id = $_GET['after_id'];

        $response = History::where('id_history','>', $id)
                            ->orderBy('timestamp')
                            ->get();
        return \Response::json($response);    
    }
}
