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
        <div class="panel-footer padding-clear-cx">
            <span class="pull-left">
                {{ $water_level_thing->module->status ? 'Online' : 'Offline' }}
            </span>
            <span class="pull-right">
                <div class="input-group">
                    <input type="checkbox" name="motor-switch" data-size="small">
                </div>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


<div class="col-lg-3 col-md-6">
    <div class="panel clr-pick-panel" style="border-color:#{{ $led_thing->latestValue->value_str }};background-color:#{{ $led_thing->latestValue->value_str }};color:white">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                    <i class="fa fa-tasks fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class="huge">{{ $led_switch->latestValue && $led_switch->latestValue->value ? 'ON' : 'OFF' }}</div>
                    <div>Backlit LED</div>
                    <div id="cp2" class="input-group colorpicker-component input-group-sm pull-right light-color">
                        <input type="text" value="#00AABB" class="form-control hide" name="led-color"/>
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer padding-clear-cx" style="color:#333;">
            <span class="pull-left">
                {{ $led_switch->module->status ? 'Online' : 'Offline' }}
            </span>
            <span class="pull-right">
                <div class="input-group">
                    <input type="checkbox" name="led-switch" data-size="small">
                </div>
            </span>
            <div class="clearfix"></div>
        </div>

    </div>
</div>


<div class="col-lg-3 col-md-6">
    <div class="panel panel-yellow">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                    <i class="fa fa-tasks fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class="huge">{{ $lcd_switch->latestValue && $lcd_switch->latestValue->value ? 'ON' : 'OFF' }}</div>
                    <div>Mirror Display</div>
                </div>
            </div>
        </div>
        <div class="panel-footer padding-clear-cx">
            <span class="pull-left">
                {{ $lcd_switch->module->status ? 'Online' : 'Offline' }}
            </span>
            <span class="pull-right">
                <div class="input-group">
                    <input type="checkbox" name="lcd-switch" data-size="small">
                </div>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>
</div>


<div class="col-lg-3 col-md-6">
    <div class="panel panel-red">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-3">
                    <i class="fa fa-tasks fa-5x"></i>
                </div>
                <div class="col-xs-9 text-right">
                    <div class="huge">{{ $light_switch->latestValue && $light_switch->latestValue->value ? 'ON' : 'OFF' }}</div>
                    <div>Main Light</div>
                </div>
            </div>
        </div>
        <div class="panel-footer padding-clear-cx">
            <span class="pull-left">
                {{ $light_switch->module->status ? 'Online' : 'Offline' }}
            </span>
            <span class="pull-right">
                <div class="input-group">
                    <input type="checkbox" name="light-switch" data-size="small">
                </div>
            </span>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<style>
    .padding-clear-cx{
        padding:4px 15px !important;
    }
    .padding-clear-cx .pull-left{
        padding-top:3px;
    }
    .light-color span{
        width: 23px;
        padding: 0px;
        margin: 0px;
        height: 22px;
    }
</style>