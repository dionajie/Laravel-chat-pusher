@extends('chat.app2')

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <h1>  Chatting </h1>

    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-danger direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                      <h3 class="box-title">Direct Chat</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i> </button>
                        </div>
                    </div>

                    <div class="box-body">
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

                <script id="chat_message_template" type="text/template"  >
                    <div class="direct-chat-msg text-display">
                        <div class="direct-chat-info clearfix">
                            <span class="direct-chat-name pull-left"> </span>
                            <span class="direct-chat-timestamp pull-right"> </span>
                        </div>

                        <img class="direct-chat-img" src="" alt="Message User Image">

                        <div class="direct-chat-text"> </div>
                    </div>
                </script>
            </div>
        </div>
    </section>
</div>



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
        el.find('.direct-chat-text').html(data.text);
        el.find('.direct-chat-name').html(data.username);
        // el.find('.author').text(data.username);
        el.find('.direct-chat-img').attr('src', data.avatar)
        
        // Utility to build nicely formatted time
        el.find('.direct-chat-timestamp').text(strftime('%H:%M:%S %P', new Date(data.timestamp)));
        
        var messages = $('.direct-chat-messages');

        messages.append(el)
        
        // Make sure the incoming message is shown
        messages.scrollTop(messages[0].scrollHeight);
    }

    // Creates an activity element from the template
    function createMessageEl() {
        var text = $('#chat_message_template').text();
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


@endsection