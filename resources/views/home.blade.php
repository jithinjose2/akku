@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">


                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-comments fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge water_level">{{ $water_level_thing->latestValue->value }}</div>
                                            <div>Water Level</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer">
                                        <span class="pull-left">
                                            {{ $water_level_thing->module->status ? 'Online' : 'Offline' }}
                                        </span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-tasks fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge">{{ $motor_switch_thing->latestValue && $motor_switch_thing->latestValue->value ? 'ON' : 'OFF' }}</div>
                                            <div>Motor Status</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="#">
                                    <div class="panel-footer">
                                        <span class="pull-left">
                                            {{ $water_level_thing->module->status ? 'Online' : 'Offline' }}
                                        </span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        @include('graph')
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var user_id = 0;
    var requested_motor_status = 0;
    $(document).ready(function() {

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

            if(res.action=='registred'){
                $('#status').html('<span class="label label-success">Registred</span>');
                $('#chat_button').removeAttr('disabled');
                $('#text_message').removeAttr('disabled');
                $('#chkbox').bootstrapSwitch('disabled', false);
                $('#chkbox').bootstrapSwitch('state', res.motor_switch_status == 1 ? true : false, true);

            } else if(res.action == 'water_level_update') {
                var series = $('#containerhc').highcharts().series[0];
                var x = (new Date()).getTime(), // current time
                        y = res.value;
                series.addPoint([x, y], true, true);
                $('#chkbox').bootstrapSwitch('state', res.switch == 1 ? true : false, true);
                $(".water_level").html(res.value);
            } else if(res.action == 'motor_switch_status') {
                $('#wait').hide();
                $('#chkbox').bootstrapSwitch('disabled', false);
            }

        }

        function register_user(){
            payload = new Object();
            payload.action 		= 'register';
            payload.key 		= 'MODULE05';
            socket.send(JSON.stringify(payload));
        }

        /*
        // sending data to websocket server
         payload = new Object();
         payload.action 		= 'chat_text';
         payload.user_id 	= user_id;
         payload.chat_text   = $('#text_message').val();
         socket.send(JSON.stringify(payload));
         */

        $("[name='my-checkbox']").bootstrapSwitch();
        $('#chkbox').bootstrapSwitch('disabled', true);


        $('#chkbox').on('switchChange.bootstrapSwitch', function(event, state) {
            $('#chkbox').bootstrapSwitch('disabled', true);
            $('#wait').show();
            payload = new Object();
            payload.action 	= 'change_switch_status';
            payload.value 		= state ? 1 : 0;
            payload.switch_key = 'MOTOR01';
            socket.send(JSON.stringify(payload));
            requested_motor_status = payload.value;
        });

    });
</script>
@endsection
