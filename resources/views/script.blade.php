<script>
    var user_id = 0;
    $(document).ready(function() {

        _motor_switch   = $("[name='motor-switch']");
        _led_switch     = $("[name='led-switch']");
        _lcd_switch     = $("[name='lcd-switch']");
        _light_switch   = $("[name='light-switch']");
        _light_color    = $("[name='led-color']").parent();
        _color_panel     = $(".clr-pick-panel");

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
                    }
                    ,5000);
            setTimeout(function(){
                        location.reload();
                    }
                    ,10000);
            return true;
        };

        function checkJson(res) {

            if(res.action == 'registred'){
                $('#status').html('<span class="label label-success">Registred</span>');
                $('#chat_button').removeAttr('disabled');
                $('#text_message').removeAttr('disabled');

                _motor_switch.bootstrapSwitch('disabled', false);
                _motor_switch.bootstrapSwitch('state', res.motor_switch_status == 1 ? true : false, true);
                _led_switch.bootstrapSwitch('disabled', false);
                _led_switch.bootstrapSwitch('state', res.led_switch_status == 1 ? true : false, true);
                _lcd_switch.bootstrapSwitch('disabled', false);
                _lcd_switch.bootstrapSwitch('state', res.lcd_switch_status == 1 ? true : false, true);
                _light_switch.bootstrapSwitch('disabled', false);
                _light_switch.bootstrapSwitch('state', res.light_switch_status == 1 ? true : false, true);

                //led_color
                _light_color.colorpicker('enable');
                _light_color.colorpicker('setValue', res.led_color)

            } else if(res.action == 'water_level_update') {
                var series = $('#graph_water').highcharts().series[0];
                var x = (new Date()).getTime(), // current time
                        y = res.value - settings.max_level;
                series.addPoint([x, y], true, true);
                $("#panel-water .xvalue").html(res.value);
            } else if(res.action == 'temperature_update') {
                var series = $('#graph_temp').highcharts().series[0];
                var x = (new Date()).getTime(), // current time
                        y = res.value;
                series.addPoint([x, y], true, true);
                $("#panel-temp .xvalue").html(res.value);
            } else if(res.action == 'humidity_update') {
                var series = $('#graph_hum').highcharts().series[0];
                var x = (new Date()).getTime(), // current time
                        y = res.value;
                series.addPoint([x, y], true, true);
                $("#panel-hum .xvalue").html(res.value);
            } else if(res.action == 'motor_switch_status') {
                _motor_switch.bootstrapSwitch('disabled', false);
                _motor_switch.bootstrapSwitch('state', res.value == 1 ? true : false, true);
                _motor_switch.parents(".panel").first().find(".huge").html(res.value == 1 ? 'ON' : 'OFF');
            } else if(res.action == 'switch_led_update') {
                _led_switch.bootstrapSwitch('disabled', false);
                _led_switch.bootstrapSwitch('state', res.value == 1 ? true : false, true);
                _led_switch.parents(".panel").first().find(".huge").html(res.value == 1 ? 'ON' : 'OFF');
            } else if(res.action == 'switch_lcd_update') {
                _lcd_switch.bootstrapSwitch('disabled', false);
                _lcd_switch.bootstrapSwitch('state', res.value == 1 ? true : false, true);
                _lcd_switch.parents(".panel").first().find(".huge").html(res.value == 1 ? 'ON' : 'OFF');
            } else if(res.action == 'switch_light_update') {
                _light_switch.bootstrapSwitch('disabled', false);
                _light_switch.bootstrapSwitch('state', res.value == 1 ? true : false, true);
                _light_switch.parents(".panel").first().find(".huge").html(res.value == 1 ? 'ON' : 'OFF');
            } else if(res.action == 'led_color_update') {
                _light_color.colorpicker('enable');
                //_light_color.colorpicker().setColor(res.value);
                _color_panel.css('background-color', '#' + res.value);
                _color_panel.css('border-color', '#' + res.value);
            }

        }

        function register_user(){
            payload = new Object();
            payload.action      = 'register';
            payload.key 		  = 'MODULE05';
            socket.send(JSON.stringify(payload));
        }


        _motor_switch.bootstrapSwitch();
        _motor_switch.bootstrapSwitch('disabled', true);
        _motor_switch.on('switchChange.bootstrapSwitch', function(event, state) {
            _motor_switch.bootstrapSwitch('disabled', true);
            payload = new Object();
            payload.action 	= 'change_switch_status';
            payload.value 		= state ? 1 : 0;
            payload.switch_key = 'MOTOR01';
            socket.send(JSON.stringify(payload));
        });

        _led_switch.bootstrapSwitch();
        _led_switch.bootstrapSwitch('disabled', true);
        _led_switch.on('switchChange.bootstrapSwitch', function(event, state) {
            _led_switch.bootstrapSwitch('disabled', true);
            payload = new Object();
            payload.action 	= 'change_switch_status';
            payload.value 		= state ? 1 : 0;
            payload.switch_key = 'SWITCHLED01';
            socket.send(JSON.stringify(payload));
        });

        _lcd_switch.bootstrapSwitch();
        _lcd_switch.bootstrapSwitch('disabled', true);
        _lcd_switch.on('switchChange.bootstrapSwitch', function(event, state) {
            _lcd_switch.bootstrapSwitch('disabled', true);
            payload = new Object();
            payload.action 	= 'change_switch_status';
            payload.value 		= state ? 1 : 0;
            payload.switch_key = 'SWITCHLCD02';
            socket.send(JSON.stringify(payload));
        });

        _light_switch.bootstrapSwitch();
        _light_switch.bootstrapSwitch('disabled', true);
        _light_switch.on('switchChange.bootstrapSwitch', function(event, state) {
            _light_switch.bootstrapSwitch('disabled', true);
            payload = new Object();
            payload.action 	= 'change_switch_status';
            payload.value 		= state ? 1 : 0;
            payload.switch_key = 'SWITCHLIGHT01';
            socket.send(JSON.stringify(payload));
        });

        _light_color.colorpicker();
        _light_color.colorpicker('disable');
        _light_color.colorpicker().on('hidePicker', function(e) {
            _light_color.colorpicker('disable');
            var color = e.color.toHex();
            if(color.length == 7) {
                color = color.substr(1);
                payload = new Object();
                payload.action = 'change_led_color';
                payload.value = color;
                payload.led_key = 'LED01';
                socket.send(JSON.stringify(payload));
            }
        });


    });
</script>