<div class="panel panel-primary" id="panel-water">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
                <i class="fa fa-comments fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
                <div class="huge xvalue">{{ $water_level_thing->latestValue->value }}</div>
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

<div class="panel panel-green" id="panel-temp">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
                <i class="fa fa-comments fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
                <div class="huge water_level">{{ $temprature_thing->latestValue->value }}</div>
                <div>Temperature</div>
            </div>
        </div>
    </div>
    <a href="#">
        <div class="panel-footer">
            <span class="pull-left">
                {{ $temprature_thing->module->status ? 'Online' : 'Offline' }}
            </span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
        </div>
    </a>
</div>

<div class="panel panel-yellow" id="panel-hum">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
                <i class="fa fa-comments fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
                <div class="huge water_level">{{ $humidity_thing->latestValue->value }}</div>
                <div>Humidity</div>
            </div>
        </div>
    </div>
    <a href="#">
        <div class="panel-footer">
            <span class="pull-left">
                {{ $humidity_thing->module->status ? 'Online' : 'Offline' }}
            </span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
        </div>
    </a>
</div>

<script>

    var _graph_water = $("#graph_water");
    var _graph_temp  = $("#graph_temp");
    var _graph_hum   = $("#graph_hum");
    var _graph_panel = $("#graph-panel");

    $(document).ready(function(){
        $("#panel-water").click(function () {
            _graph_water.show();
            _graph_temp.hide();
            _graph_hum.hide();
            _graph_panel.removeClass('panel-yellow').removeClass('panel-green').addClass('panel-primary');
        });

        $("#panel-temp").click(function () {
            _graph_water.hide();
            _graph_temp.show();
            _graph_hum.hide();
            _graph_panel.removeClass('panel-yellow').removeClass('panel-primary').addClass('panel-green');
        });

        $("#panel-hum").click(function () {
            _graph_water.hide();
            _graph_temp.hide();
            _graph_hum.show();
            _graph_panel.removeClass('panel-green').removeClass('panel-primary').addClass('panel-yellow');
        });
    })
</script>
<style>
    #panel-water{
        cursor: pointer;
    }
    #panel-temp{
        cursor: pointer;
    }
    #panel-hum{
        cursor: pointer;
    }
</style>