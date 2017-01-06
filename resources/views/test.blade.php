<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">
<html>
    <head>
        <link rel="stylesheet" href="http://techzonemind.com/labz/wsphpserver/css/bootstrap.min.css">
        <link rel="stylesheet" href="http://techzonemind.com/labz/wsphpserver/css/todc-bootstrap.min.css">
		<link rel="stylesheet" href="http://techzonemind.com/labz/wsphpserver/css/bootstrap-switch.css">
        <title>LightControll</title>
		<script src="http://techzonemind.com/labz/wsphpserver/js/jquery.min.js"></script>
        <script src="http://techzonemind.com/labz/wsphpserver/js/bootstrap.min.js"></script>
		<script src="http://techzonemind.com/labz/wsphpserver/js/highcharts.js"></script>
		<script src="http://techzonemind.com/labz/wsphpserver/js/bootstrap-switch.js"></script>
    </head>
    <body>
        
        <nav class="navbar navbar-toolbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".bs-example-toolbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="" class="navbar-brand dropdown-toggle" data-toggle="dropdown">
						LightControll - Controll Light From anywhere in the world.
					</a>
                </div>
                <div class="collapse navbar-collapse bs-example-toolbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="#">LightControll</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="page-header">
                        <h1>LightControll<small>&nbsp;&nbsp;Controll Light From anywhere...</small></h1>
                    </div>
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <span id="chat-head">LightControll</span>
                            <span id="status" style="float:right">
                                <span class="label label-info">Info</span>
                            </span>
                        </div>
                        <div class="panel-body" style="min-height:250px">
                            <div id="containerhc" style="min-width: 310px; height: 250px; margin: 0 auto"></div>
                        </div>
                        <div class="panel-footer">
                            <div class="input-group">
								Light Switch
                                <input type="checkbox" name="my-checkbox" id="chkbox" data-size="large">
								<span id="wait" style="display: none;color:grey">&nbsp;&nbsp;Please wait.....&nbsp;</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <nav class="navbar navbar-toolbar navbar-default navbar-fixed-bottom" role="navigation">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="bs-example-navbar-toolbar-collapse-7">
                    <ul class="nav navbar-nav" style="width: 100%">
                        <li class="active"><a class="navbar-brand" href="http://www.techzonemind.com/" style="color: #DD4B39;background-color: white">TZM Labs</a></li>
                        <li><a href="http://www.techzonemind.com/category/articles/">Article</a></li>
                        <li><a href="http://www.techzonemind.com/category/hall-of-frame/">Hall Of frame</a></li>
                        <li><a href="http://www.techzonemind.com/category/libraries/">Libraries</a></li>
                        <li><a href="http://www.techzonemind.com/category/tutorials/">Tutorials</a></li>
                        <li><a href="http://www.techzonemind.com/category/tools/">Tools</a></li>
                        <li style="float: right"><a href="">Copyright 2014 TechZoneMind</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        
        
        <script>
            var user_id = 0;
            
            $(document).ready(function() {
		
                serverUrl = 'ws://localhost:8000/demo';
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
                        $('#chat-head').html('<b>User-'+res.user_id+'</b> ('+res.users_online+' Users Online)');
                        user_id = res.user_id;
                        
                    }else if(res.action=='light_update'){
                        var series = $('#containerhc').highcharts().series[0];
						var x = (new Date()).getTime(), // current time
							y = res.light;
						series.addPoint([x, y], true, true);
						$('#chkbox').bootstrapSwitch('state', res.switch == 1 ? true : false, true);
                    }
                    
                }
                
                function register_user(){
                    payload = new Object();
                    payload.action 		= 'register';
                    socket.send(JSON.stringify(payload));
                }
                
                
                $("#chat_button").click(function(){
                    payload = new Object();
                    payload.action 		= 'chat_text';
                    payload.user_id 	= user_id;
                    payload.chat_text   = $('#text_message').val();
                    socket.send(JSON.stringify(payload));
                });
                
                $("#text_message").on("keypress", function(e) {
                    if (e.keyCode == 13){
                        $("#chat_button").click();
                    }
                });
				
				$("[name='my-checkbox']").bootstrapSwitch();
				
				$('#chkbox').on('switchChange.bootstrapSwitch', function(event, state) {
					$('#chkbox').bootstrapSwitch('disabled', true);
					$('#wait').show();
					setTimeout(function() {
						$('#wait').hide();
						$('#chkbox').bootstrapSwitch('disabled', false);
					}, 10000);
					
                    payload = new Object();
                    payload.action 		= 'switch';
					payload.status 		= state ? 1 : 0;
                    socket.send(JSON.stringify(payload));
				});
                
            });
        </script>
		<script>
			$(function () {
				$(document).ready(function () {
					
					Highcharts.setOptions({
						global: {
							useUTC: false
						}
					});
			
					$('#containerhc').highcharts({
						chart: {
							type: 'spline',
							animation: Highcharts.svg, // don't animate in old IE
							marginRight: 10,
							events: {
								load: function () {
			
									// set up the updating of the chart each second
									/*var series = this.series[0];
									setInterval(function () {
										
									}, 1000);*/
								}
							}
						},
						title: {
							text: 'Light Intensity'
						},
						xAxis: {
							type: 'datetime',
							tickPixelInterval: 900
						},
						yAxis: {
							title: {
								text: 'Light Intensity'
							},
							plotLines: [{
								value: 0,
								width: 1,
								color: '#808080'
							}],
							min : 0,
							max : 100
						},
						tooltip: {
							formatter: function () {
								return '<b>' + this.series.name + '</b><br/>' +
									Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
									Highcharts.numberFormat(this.y, 2);
							}
						},
						legend: {
							enabled: false
						},
						exporting: {
							enabled: false
						},
						series: [{
							name: 'Light Intensity',
							data: (function () {
								// generate an array of random data
								var data = [],
									time = (new Date()).getTime(),
									i;
			
								for (i = -19; i <= 0; i += 1) {
									data.push({
										x: time + i * 1000,
										y: 0
									});
								}
								return data;
							}())
						}]
					});
				});
			});
		</script>
    </body>
</html>