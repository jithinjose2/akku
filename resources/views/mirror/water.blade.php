<div class="water">
    <div class="section_1">
        <div class="tank">
            <div class="liquid"></div>
        </div>
        <div class="info">
            <div class="water_percent">23 %</div>
            <div class="water_trigger">Trigger 25% - 90%</div>
            <div class="water_cutoff">Last Updated 10:20</div>
        </div>
    </div>
    <div class="section_2">
        @include('mirror.chart')
    </div>
</div>
<style>
    .section_1{
        float: left;
        width: 250px;
    }
    .water{
    
    }
    .water .tank{
        border-width: 0px 1px 1px 1px !important;
        border: solid 0px rgba(255, 255, 255, 0.67);
        margin: 18px;
        width: 60px;
        height: 80px;
        position: relative;
        float: left;
    }
    .liquid{
        position: absolute;
        bottom: 0px;
        height: 10px;
        background-color: rgba(175, 225, 255, 0.82);
        width: 100%;
    }
    .water_percent{
        font-size: 40px;
        padding-top:10px;
    }
    .water_trigger, .water_cutoff{
        color: grey;
        font-size:15px;
    }
    .section_2{
        float: left;
        width:300px;
        height:100px;
    }
</style>
