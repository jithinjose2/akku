<script>

    var recognition = new webkitSpeechRecognition();
    recognition.continuous = false;
    recognition.interimResults = true;
    recognition.lang = 'en-US';
    recognition.start();

    recognition.onstart = function(event){
        //console.log("onstart", event);
    }

    // Process parsed result
    recognition.onresult = function(event){
        var interim_transcript = '', final_transcript='';
        for (var i = event.resultIndex; i < event.results.length; ++i) {
            if (event.results[i].isFinal) {
                final_transcript += event.results[i][0].transcript;
            } else {
                interim_transcript += event.results[i][0].transcript;
            }
        }
        processVoiceCommand(interim_transcript);
    }

    // Handle error
    recognition.onerror = function(event){
        //console.log("onerror", event);
    }

    // Housekeeping after success or failed parsing
    recognition.onend = function(){
        recognition.start();
    }


    function processVoiceCommand(command){
        console.log('voice command - ' + command);
        var object = '', action = '', value ='';

        command = command.trim();

        if(command.length > 0) {
            $(".vc").html(command);
            // objects
            if (command.indexOf('light') >= 0 || command.indexOf('bulb') >= 0 || command.indexOf('length') >= 0 || command.indexOf('like') >= 0) {
                object = 'light';
            }
            if (command.indexOf('LED') >= 0 || command.indexOf('backlit') >= 0) {
                object = 'led';
            }
            if (command.indexOf('motor') >= 0 || command.indexOf('pump') >= 0) {
                object = 'motor';
            }

            // Values
            if (command.indexOf('on') >= 0) {
                value = 'on';
            }
            if (command.indexOf('off') >= 0 || command.indexOf(' of') >= 0) {
                value = 'off';
            }

            // Change color
            if (command.indexOf('red') >= 0 || command.indexOf('read') >= 0) {
                action = 'update_color';
                object = 'led';
                value = 'red';
            }
            if (command.indexOf('blue') >= 0) {
                action = 'update_color';
                object = 'led';
                value = 'blue';
            }
            if (command.indexOf('green') >= 0 || command.indexOf('Green') >= 0) {
                action = 'update_color';
                object = 'led';
                value = 'green';
            }
            if (command.indexOf('violet') >= 0 || command.indexOf('violent') >= 0 || command.indexOf('Twilight') >= 0) {
                action = 'update_color';
                object = 'led';
                value = 'violet';
            }
            if (command.indexOf('yellow') >= 0) {
                action = 'update_color';
                object = 'led';
                value = 'yellow';
            }
            if (command.indexOf('White') >= 0 || command.indexOf('white') >= 0) {
                action = 'update_color';
                object = 'led';
                value = 'white';
            }

            if(object.length > 0 && value.length > 0) {
                processCommand(object, action, value);
            }
        }

    }

    var last_object='', last_action='', last_value='';
    function processCommand(object, action, value)
    {
        if(last_object!=object || last_action!=action || last_value!=value) {
            $(".vc_proc").html(object + " : " + action + " : " + value);

            // according to action change color
            if(object == 'light') {
                if(value == 'on') {
                    _compliment.updateWithText("Okay, Turning On Light", 2000);
                    changeSwitchStatus('SWITCHLIGHT01', 1);
                }
                if(value == 'off') {
                    _compliment.updateWithText("Okay, Turning off Light", 2000);
                    changeSwitchStatus('SWITCHLIGHT01', 0);
                }
            }

            if(object == 'led') {
                if(action == 'update_color' && value.length>0) {
                    _compliment.updateWithText("Okay, Changing LED color to " + value, 2000);
                    var color = 'FFFFFF';
                    if(value == 'red') color = 'FF0000';
                    if(value == 'green') color = '00FF00';
                    if(value == 'blue') color = '0000FF';
                    if(value == 'yellow') color = 'FFFF00';
                    if(value == 'white') color = 'FFFFFF';
                    if(value == 'violet') color = 'EE82EE';
                    if(value == 'red') color = 'FF0000';
                    updateLEDColor(color);

                } else {
                    if (value == 'on') {
                        _compliment.updateWithText("Okay, Turning On Backlit", 2000);
                        changeSwitchStatus('SWITCHLED01', 1);
                    }
                    if (value == 'off') {
                        _compliment.updateWithText("Okay, Turning off Backlit", 2000);
                        changeSwitchStatus('SWITCHLED01', 0);
                    }
                }
            }

            if(object == 'motor') {
                if(value == 'on') {
                    _compliment.updateWithText("Okay, Turning On Water Pump", 2000);
                    changeSwitchStatus('MOTOR01', 1);
                }
                if(value == 'off') {
                    _compliment.updateWithText("Okay, Turning off Water Pump", 2000);
                    changeSwitchStatus('MOTOR01', 0);
                }
            }
        }
        last_object = object;
        last_action = action;
        last_value = value;
    }

    function changeSwitchStatus(key, status)
    {
        payload = new Object();
        payload.action 	= 'change_switch_status';
        payload.value 		= status ? 1 : 0;
        payload.switch_key = key;
        socket.send(JSON.stringify(payload));
    }

    function updateLEDColor(color)
    {
        payload = new Object();
        payload.action = 'change_led_color';
        payload.value = color;
        payload.led_key = 'LED01';
        socket.send(JSON.stringify(payload));
    }
</script>