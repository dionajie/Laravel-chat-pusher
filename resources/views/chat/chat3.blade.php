@extends('chat.app')

@section('content')
<div class="chat_window">
    <div class="top_menu">
        <div class="buttons">
            <div class="button close"> </div>
            <div class="button minimize"> </div>
            <div class="button maximize"> </div>
        </div>

        <div class="title">Chat</div>
    </div>
            
    <ul class="messages"> </ul>
    <div class="bottom_wrapper clearfix">
        <div class="message_input_wrapper">
            <input class="message_input input-message" placeholder="Type your message here..." />
        </div>
        <div class="send_message">
            
            <div class="icon">   </div>
            <div class="text send-message"> Send </div>
        </div>
    </div>
</div>

<script class="message_template" type="text/template">
    <li class="message">
        <div class="avatar">
            <img src="">
        </div>
        <div class="text_wrapper">
            <div class="text message-body">
                
            </div>
        </div>
    </li>
</script>

{{-- <section class="blue-gradient-background">
    <div class="container">
        <div class="row light-grey-blue-background chat-app">

            <div id="messages">
                <div class="time-divide">
                    <span class="date">Today</span>
                </div>
            </div>

            <div class="action-bar">
                <textarea class="input-message col-xs-10" placeholder="Your message"></textarea>
                <div class="option col-xs-1 white-background">
                    <span class="fa fa-smile-o light-grey"></span>
                </div>
                <div class="option col-xs-1 green-background send-message">
                    <span class="white light fa fa-paper-plane-o"></span>
                </div>
            </div>

        </div>
    </div>
</section>

<script id="chat_message_template" type="text/template">
    <div class="message">
        <div class="avatar">
            <img src="">
        </div>
        <div class="text-display">
            <div class="message-data">
                <span class="author"></span>
                <span class="timestamp"></span>
                <span class="seen"></span>
            </div>
            <p class="message-body"></p>
        </div>
    </div>
</script> --}}

<script>
    function init() {
        // send button click handling
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

        // Build POST data and make AJAX request
        var data = {chat_text: messageText};
        $.post('/chat/message', data).success(sendMessageSuccess);

        // Ensure the normal browser event doesn't take place
        return false;
    }

    // Handle the success callback
    function sendMessageSuccess() {
        $('.input-message').val('')
        console.log('message sent successfully');
    }

    // Build the UI for a new message and add to the DOM
    function addMessage(data) {
        // Create element from template and set values
        var el = createMessageEl();
        el.find('.message-body').html(data.text);
        // el.find('.author').text(data.username);
        el.find('.avatar img').attr('src', data.avatar)
        
        // Utility to build nicely formatted time
        // el.find('.timestamp').text(strftime('%H:%M:%S %P', new Date(data.timestamp)));
        
        var messages = $('.messages');

        messages.append(el)
        
        // Make sure the incoming message is shown
        messages.scrollTop(messages[0].scrollHeight);
    }

    // Creates an activity element from the template
    function createMessageEl() {
        var text = $('.message_template').text();
        console.log(text)
        var el = $(text);
        return el;
    }

    $(init);

    /***********************************************/

    var pusher = new Pusher("{{env("PUSHER_KEY")}}", { 
                    cluster: 'ap1' 
                })

    var channel = pusher.subscribe('{{$chatChannel}}');
    channel.bind('new-message', addMessage);

</script>

<script type="text/javascript">
    
    (function () {
    var Message;
    Message = function (arg) {
        this.text = arg.text, this.message_side = arg.message_side;
        this.draw = function (_this) {
            return function () {
                var $message;
                $message = $($('.message_template').clone().html());
                $message.addClass(_this.message_side).find('.text').html(_this.text);
                $('.messages').append($message);
                return setTimeout(function () {
                    return $message.addClass('appeared');
                }, 0);
            };
        }(this);
        return this;
    };
    $(function () {
        var getMessageText, message_side, sendMessage;
        message_side = 'right';
        getMessageText = function () {
            var $message_input;
            $message_input = $('.message_input');
            return $message_input.val();
        };
        sendMessage = function (text) {
            var $messages, message;
            if (text.trim() === '') {
                return;
            }
            $('.message_input').val('');
            $messages = $('.messages');

            var data = {chat_text: messageText};
            $.post('/chat/message', data).success(sendMessageSuccess);
            
            message_side = message_side === 'left' ? 'right' : 'left';
            message = new Message({
                text: text,
                message_side: message_side
            });
            message.draw();
            return $messages.animate({ scrollTop: $messages.prop('scrollHeight') }, 300);
        };
        $('.send_message').click(function (e) {
            return sendMessage(getMessageText());
        });
        $('.message_input').keyup(function (e) {
            if (e.which === 13) {
                return sendMessage(getMessageText());
            }
        });
        sendMessage('Hello Philip! :)');
        setTimeout(function () {
            return sendMessage('Hi Sandy! How are you?');
        }, 1000);
        return setTimeout(function () {
            return sendMessage('I\'m fine, thank you!');
        }, 2000);
    });
}.call(this));

      /***********************************************/

    var pusher = new Pusher("{{env("PUSHER_KEY")}}", { 
                    cluster: 'ap1' 
                })

    var channel = pusher.subscribe('{{$chatChannel}}');
    channel.bind('new-message', addMessage);
</script>

@endsection