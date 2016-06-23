<div class="water ws_show">
    <div class="section_1">
        <div class="tank">
            <div class="liquid"></div>
        </div>
        <div class="info">
            <div class="water_percent">0 %</div>
            <div class="water_motor"><img src="img/motor_1.gif"/></div>
            <div class="water_trigger">Trigger <span id="trigger_percent">0</span>% - <span id="cutoff_percent">0</span>%</div>
            <div class="water_time">Last Updated 00:00</div>
        </div>
    </div>
    <div class="section_2">
        @include('mirror.chart')
    </div>
</div>
<div class="temp_hum ws_show">
    Temp <span class="ws_temp">00</span>°<br>
    Humidity <span class="ws_hum">00</span>%</span>
</div>
<style>
    .temp_hum{
        font-size:15px;
        color: grey;
        clear: both;
    }
    .section_1{
        float: left;
        width: 250px;
    }
    .water{
    
    }
    .water .tank{
        border-width: 0px 1px 1px 1px !important;
        border: solid 0px rgba(255, 255, 255, 0.29);
        margin: 18px;
        width: 60px;
        height: 80px;
        position: relative;
        float: left;
    }
    .liquid{
        position: absolute;
        bottom: 0px;
        height: 0px;
        background-color: rgba(175, 225, 255, 0.82);
        width: 100%;
    }
    .water_percent{
        font-size: 40px;
        padding-top:10px;
        float: left;
        width:100px;
    }
    .water_trigger, .water_time{
        color: grey;
        font-size:15px;
    }
    .section_2{
        float: left;
        width:300px;
        height:100px;
    }
    .water_motor{
        padding-top: 20px;
        height: 40px;
    }
    .water_motor img{
        display: none;
    }
</style>
