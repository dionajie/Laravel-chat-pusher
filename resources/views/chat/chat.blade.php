@extends('chat.app')

@section('content')
<style type="text/css">

    .online_item {
        border-bottom: 1px solid #d6d6d6 !important;
        padding : 0px 10px 5px 10px !important;
        margin-bottom: 10px;
    }
    .online_item>img {
        width: 40px;
        height: 40px;
        border: 2px solid transparent;
        border-radius: 50%;
    }

    .online_item>.online {
        border: 2px solid #00a65a;
    }

</style>
<div class="content-wrapper">

    <section class="content-header">
        <h1>  General Group </h1>
    </section>

    <section class="content">
        <div class="row">

            <!-- ============ Chat Interface ================-->
            <div class="col-md-6 col-xs-12 col-sm-9">
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-comments-o"> </i>
                        <h3 class="box-title">Chat</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                        </div>
                    </div>

                    <div class="box-body">
                        {{-- <div class="online_item container">
                            @foreach($users as $user)
                                <img class="ava"  id="user-{{$user->username}}" src="{{ $user->avatar }}" alt="{{ $user->name }}" >
                            @endforeach
                        </div> --}}
                        <div class="direct-chat-messages">
                            <!-- Message Here -->
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <div class="input-group">
                            <input class="input-message form-control" type="text" name="message" placeholder="Type Message ...">
                            <span class="input-group-btn">
                                <button class="btn btn-primary btn-flat send-message">Send</button>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Other Users chat -->
                <script id="chat_message_template" type="text/template"  >
                    <div class="direct-chat-msg text-display col-md-9">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left"> </span>
                            <span class="direct-chat-timestamp pull-right"> </span>
                        </div>

                        <img class="direct-chat-img" src="" alt="Message User Image">

                        <div class="direct-chat-text"> </div>
                    </div>
                </script>

                <!-- My chat -->
                <script id="chat_message_template_right" type="text/template"  >
                    <div class="direct-chat-msg right text-display col-md-9 col-md-offset-3">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-right"> </span>
                            <span class="direct-chat-timestamp pull-left"> </span>
                        </div>

                        <img class="direct-chat-img" src="" alt="Message User Image">

                        <div class="direct-chat-text"> </div>
                    </div>
                </script>
            </div>


            <!-- ============ Group member and online user ================-->
            <div class="col-md-6 col-xs-12 col-sm-9">
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-comments-o"> </i>
                        <h3 class="box-title">Member</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="online_item container">
                            @foreach($users as $user)
                                <img class="ava"  id="user-{{$user->username}}" src="{{ $user->avatar }}" alt="{{ $user->name }}" >
                            @endforeach
                        </div>
                              
                    </div>
                    
                    <div class="box-footer">
                       
                    </div>
                </div>
            </div>

            <script id="user_online" type="text/template"  >
                <div class="text-display col-md-9 col-md-offset-3">
                   
                </div>
            </script>
        </div>
    </section>
</div>



<script>

    var lastId;

    // get chat history 
    function getHistory() {
        lastId = 0;
        $.get('/chat/message', {after_id: lastId} ).success(function(response) {
            var length = response.length;
           
            $.each( response, function( key, value ) {
                addMessageToUI(response[key]);
                if(key == length-1) {
                    lastId = value['id_history']; 
                }
            });
  
        });
        
    };


    // init
    function init() {
        $('.send-message').click(sendMessage);
        $('.input-message').keypress(checkSend);
    }

    // Send on enter/return key
    function checkSend(e) {
        if (e.keyCode === 13) {
            return sendMessage();
        }
    }

    // Handle the send button being clicked
    function sendMessage() {
        var messageText = $('.input-message').val();
        if(messageText.length < 3) {
            return false;
        }

        var data = {chat_text: messageText};
        $.post('/chat/message', data).success(sendMessageSuccess);

        return false;
    }

    // Handle the success callback
    function sendMessageSuccess() {
        $('.input-message').val('');
    }

    // Get message from last id
    function addMessage(data) {
        $.get('/chat/message', {after_id: lastId}).success(function(response) {

            var length = response.length;

            $.each( response, function( key, value ) {
                addMessageToUI(response[key]);

                if(key == length-1) {
                    lastId = value['id_history']; 
     
                }
            });
        });
        
    }

     // Build the UI for a new message and add to the DOM
    function addMessageToUI(data) {
        var user = {!! json_encode(auth()->user()->username) !!};
        var username = ''+data.name+' (@'+data.username+')'
        
        if(user === data.username) {
            var el = createMessageRightEl();
        } else {
            var el = createMessageEl();
        }
        el.find('.direct-chat-text').html(data.text);
        el.find('.direct-chat-name').html(username);
        el.find('.direct-chat-img').attr('src', data.avatar);
        el.find('.direct-chat-timestamp').text(strftime('%H:%M:%S %P', new Date(data.timestamp)));
        
        var messages = $('.direct-chat-messages');
        messages.append(el);
        messages.scrollTop(messages[0].scrollHeight);
    }

    function createMessageEl() {
        var text = $('#chat_message_template').text();
        var el = $(text);
        return el;
    }

    function createMessageRightEl() {
        var text = $('#chat_message_template_right').text();
        var el = $(text);
        return el;
    }

    function addUserOnline(data) {
        $('#user-'+data.name+'').addClass('online')
    }

    function removeUserOnline(data) {
        $('#user-'+data.name+'').removeClass('online')
    }


    $(init);


    /******************* PUSHER *********************/

    var pusher = new Pusher('{{env("PUSHER_KEY")}}', {
        cluster: 'ap1',
        authEndpoint: '/chat/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }
    });

    var presenceChannel = pusher.subscribe('{{$chatChannel}}');

    presenceChannel.bind('new-message', addMessage);

    presenceChannel.bind('pusher:subscription_succeeded', function(members) {
        getHistory()
        members.each(function(member) {
            addUserOnline(member.info);
        });
    });

    presenceChannel.bind('pusher:member_added', function(member) {
        addUserOnline(member.info);
    });

    presenceChannel.bind('pusher:member_removed', function(member) {
        removeUserOnline(member.info);
    });


</script>


@endsection