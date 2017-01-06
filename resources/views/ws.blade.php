<script>

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
        checkJson(response);
        return true;
    };
    socket.onclose = function(msg) {
        $('#status').html('<span class="label label-danger">Disconnected</span>');
        setTimeout(function(){
            $('#status').html('<span class="label label-warning">Reconnecting</span>');
        },5000);
        setTimeout(function(){
            location.reload();
        },10000);
        return true;
    };

    function register_user(){
        payload = new Object();
        payload.action      = 'register';
        payload.key 		  = 'WEB1';
        payload.pin 		  = '1234';
        socket.send(JSON.stringify(payload));
    }
</script>