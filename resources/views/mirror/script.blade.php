<script>

    var _light = $(".light");
    var _led = $(".led");
    var _percent = $(".water_percent");
    var _udpated = $(".water_time");
    var _graph = $('#water_graph_container');
    var _motor = $('.water_motor img');
    var _temp = $('.ws_temp');
    var _hum = $('.ws_hum');
    var percent = 0;
    var _motor_status = 0;
    var _compliment = $('.compliment');
    var settings = {};
    var _back = $("#lback");
    var _active_time = 0;

    // Websocket section
    //$(document).ready(function () {

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
            payload.key 		  = 'MODULE04';
            socket.send(JSON.stringify(payload));
        }

    //})


    function checkJson(res) {
        if (res.action == 'registred') {
            $('#status').hide();
            $('.ws_show').show();
            settings = res.settings;
            changeLightStatus(_led, res.led_switch_status);
            changeLightStatus(_light, res.light_switch_status);
            _led.css('background-color', '#' + res.led_color);
            setWaterLevel(res.water_level);
            motorStateChange(res.motor_switch_status);
            _temp.html(res.temperature);
            _hum.html(res.humidity);
            $("#trigger_percent").html(settings.trigger_percent);
            $("#cutoff_percent").html(settings.cutoff_percent);
        } else if (res.action == 'water_level_update') {
            setWaterLevel(res.value);
        } else if(res.action == 'led_color_update') {
            _led.css('background-color', '#' + res.value);
        } else if(res.action == 'switch_led_update') {
            changeLightStatus(_led, res.value);
        } else if(res.action == 'switch_light_update') {
            changeLightStatus(_light, res.value);
        } else if(res.action == 'motor_switch_status') {
            motorStateChange(res.value);
        } else if(res.action == 'temperature_update') {
            _temp.html(res.value);
        } else if(res.action == 'humidity_update') {
            _hum.html(res.value);
        } else if(res.action == 'motion_detected') {
            processMotion(res.value);
        }
    }


    function  changeLightStatus(light, status) {
        if(status == 1 ) {
            light.removeClass('off');
            light.addClass('on');
        } else {
            light.removeClass('on');
            light.addClass('off');
        }
    }

    function setWaterLevel(level){
        console.log("Updating water level to" + level );
        percent = (level - settings.max_level) / (settings.min_level - settings.max_level);
        percent = percent * 100;
        if(percent < 0) {
            percent = 0;
        }
        percent = Math.round(percent);
        _percent.html(percent + " %");

        var d = new Date();
        _udpated.html('Last Updated ' + d.getHours() + ':' + d.getMinutes());

        var series = _graph.highcharts().series[0];
        var x = (new Date()).getTime(), // current time
                y = percent;
        series.addPoint([x, y], true, true);
        $(".liquid").css('height', percent * 80 / 100);

    }


    function motorStateChange(value){
        if(value == 1) {
            // Motor is truned on
            _motor_status = 1;
            _graph.show("slow");
            _motor.show();
            _motor.attr("src", "img/motor_1.gif");
            setTimeout(function(){ if(_motor_status==1) _motor.attr("src", "img/motor_2.gif"); }, 1000);
            setTimeout(function(){ if(_motor_status==1) _motor.attr("src", "img/motor_3.gif"); }, 2000);
            setTimeout(function(){ if(_motor_status==1) _motor.attr("src", "img/motor_4.gif"); }, 3000);
            setTimeout(function(){ if(_motor_status==1) _motor.attr("src", "img/motor_5.gif"); }, 4000);
        } else {
            // Motor truned off
            _motor.attr("src", "img/motor_1.gif");
            _motor_status = 2;
            _graph.hide("slow");
            _motor.hide();

        }
    }

    function processMotion(value)
    {
        /*if(value == 1) {
            _active_time = 30;
            $("#lback").fadeTo("slow", 0);
        }*/
    }

    /*setInterval(function(){
        _active_time--;
        if(_active_time == 0){
            _back.fadeTo("slow", 1);
            $("#lback").fadeTo("slow", 1);
        }
    }, 1000);*/

</script>
@include('mirror.voice')