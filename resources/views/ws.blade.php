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

    function checkJson(res) {
        console.log(res);
        if (res.action == 'registred') {
            $('#status').html('<span class="label label-sucess">CONNECTED</span>');
            /*$('.ws_show').show();
            settings = res.settings;
            changeLightStatus(_led, res.led_switch_status);
            changeLightStatus(_light, res.light_switch_status);
            _led.css('background-color', '#' + res.led_color);
            setWaterLevel(res.water_level);
            motorStateChange(res.motor_switch_status);
            _temp.html(res.temperature);
            _hum.html(res.humidity);
            $("#trigger_percent").html(settings.trigger_percent);
            $("#cutoff_percent").html(settings.cutoff_percent);*/
        } else if (res.action == 'temp_update') {
            setTemperatureLevel(res.value);
        } else if(res.action == 'humid_update') {
            setHumidityLevel(res.value);
        } else if (res.action == 'switch_status_change') {
            turnSwitch(res.thing_id, res.value);
        }
    }

    function SwitchChangeByUser(thing_id, value){
        payload = new Object();
        payload.action      = 'change_switch_status';
        payload.thing_id 		  = thing_id;
        payload.value 		  = value;
        console.log(payload);
        socket.send(JSON.stringify(payload));
    }
</script>