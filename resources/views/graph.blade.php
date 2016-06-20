<div class="panel panel-primary">
    <div class="panel-heading">
        <span id="chat-head">MotorControll</span>
        <span id="status" style="float:right">
            <span class="label label-info">Info</span>
        </span>
    </div>
    <div class="panel-body" style="min-height:250px">
        <div id="containerhc" style="min-width: 310px; height: 310px; margin: 0 auto"></div>
    </div>
    <div class="panel-footer">
        Live Data
    </div>
</div>
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
                    text: 'Water Level'
                },
                xAxis: {
                    type: 'datetime',
                    tickPixelInterval: 900
                },
                yAxis: {
                    title: {
                        text: 'Water Level [mm]'
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }],
                    min : 0
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
                    name: 'Water Level',
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