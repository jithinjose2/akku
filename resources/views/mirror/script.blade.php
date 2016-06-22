<script>
    var recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'en-IN';
    recognition.start();

    recognition.onstart = function(event){
        console.log("onstart", event);
    }

    // Process parsed result
    recognition.onresult = function(event){
        console.log("onresult", event);
        var interim_transcript = '', final_transcript='';
        for (var i = event.resultIndex; i < event.results.length; ++i) {
            if (event.results[i].isFinal) {
                final_transcript += event.results[i][0].transcript;
            } else {
                interim_transcript += event.results[i][0].transcript;
            }
        }
        console.log(interim_transcript,final_transcript );
    }

    // Handle error
    recognition.onerror = function(event){
        console.log("onerror", event);
    }

    // Housekeeping after success or failed parsing
    recognition.onend = function(){
        recognition.start();
    }
</script>

<script>
    $(document).ready(function () {

        serverUrl = 'ws://{{ config('websocket.host') .':'. config('websocket.port') }}/demo';
        if (window.MozWebSocket) {
            socket = new MozWebSocket(serverUrl);
        } else if (window.WebSocket) {
            socket = new WebSocket(serverUrl);
        }
        socket.binaryType = 'blob';
        socket.onopen = function(msg) {
            $('#status').html('<span class="label label-info">Registering</span>');
            register_user();
            return true;
        };

        socket.onmessage = function(msg) {
            var response;
            response = JSON.parse(msg.data);
            //checkJson(response);
            return true;
        };
        socket.onclose = function(msg) {
            $('#status').html('<span class="label label-danger">Disconnected</span>');
            setTimeout(function(){
                $('#status').html('<span class="label label-warning">Reconnecting</span>');
            },5000);
            setTimeout(function(){
               // location.reload();
            },10000);
            return true;
        };

        function register_user(){
            payload = new Object();
            payload.action      = 'register';
            payload.key 		  = 'MODULE04';
            socket.send(JSON.stringify(payload));
            $('#status').hide();
            $('.ws_show').show();
        }

    })
</script>