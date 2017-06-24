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
use App\User;

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
        $this->chatChannel = 'presence-chat';
    }

    public function getIndex()
    {

        if(!$this->user)
        {
            return redirect('home');
        }

        $users = User::all();
        $chatChannel = $this->chatChannel;

        return view('chat.chat', compact('chatChannel','users'));
    }

    public function postAuth(Request $request)
    {

            $channelName =  $this->chatChannel;
            $socketId = $request->input('socket_id');
            $user_id = $this->user['id'];
            $presence_data = array('name' => $this->user['username']);

            $auth = $this->pusher->presence_auth($channelName, $socketId, $user_id,$presence_data);

            return response($auth);
    }

    public function postMessage(Request $request)
    {

        $message = [
            'text' => e($request->input('chat_text')),
            'name' => $this->user['name'],
            'avatar' => $this->user['avatar'],
            'username' => $this->user['username'],
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
