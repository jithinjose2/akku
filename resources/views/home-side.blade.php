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

<div class="panel panel-green">
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

<div class="panel panel-yellow">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-3">
                <i class="fa fa-comments fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
                <div class="huge water_level">{{ $humidity_thing->latestValue->value }}</div>
                <div>Water Level</div>
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
