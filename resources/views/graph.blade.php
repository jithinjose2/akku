<div class="panel panel-primary" id="graph-panel">
    <div class="panel-heading">
        <span id="chat-head">MotorControll</span>
        <span id="status" style="float:right">
            <span class="label label-info">Info</span>
        </span>
    </div>
    <div class="panel-body" style="min-height:250px">
        <div id="graph_water" style="min-width: 310px; height: 310px; margin: 0 auto"></div>
        <div id="graph_temp" style="min-width: 310px; height: 310px; margin: 0 auto"></div>
        <div id="graph_hum" style="min-width: 310px; height: 310px; margin: 0 auto"></div>
    </div>
    <div class="panel-footer">
        Live Data
    </div>
</div>
<script>

    var settings = {!! json_encode($settings) !!};

    var graph_template = {
        chart: {
            type: 'spline',
            animation: Highcharts.svg, // don't animate in old IE
            marginRight: 10
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
            min : 0,
            max : settings.min_level - settings.max_level
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
    };

    $(function () {
        $(document).ready(function () {

            Highcharts.setOptions({
                global: {
                    useUTC: false
                }
            });

            var water_graph_info = jQuery.extend(true, {}, graph_template),
                temp_graph_template = jQuery.extend(true, {}, graph_template),
                hum_graph_template = jQuery.extend(true, {}, graph_template);


            water_graph_info.yAxis.plotLines = [{
                value: (settings.min_level - settings.max_level) * settings.trigger_percent / 100,
                color: 'green',
                dashStyle: 'shortdash',
                width: 2,
                label: {
                    text: 'Motor trigger'
                }
            }, {
                value: (settings.min_level - settings.max_level) * settings.cutoff_percent / 100,
                color: 'red',
                dashStyle: 'shortdash',
                width: 2,
                label: {
                    text: 'Motor cutoff'
                }
            }];
            $('#graph_water').highcharts(water_graph_info);

            temp_graph_template.title.text = "Temperature";
            temp_graph_template.yAxis.title.text = "Temperature [C]";
            temp_graph_template.series[0].name = "Temperature";
            temp_graph_template.series[0].color = '#66FF33';
            $('#graph_temp').highcharts(temp_graph_template);

            hum_graph_template.title.text = "Humidity";
            hum_graph_template.yAxis.title.text = "Humidity";
            hum_graph_template.series[0].name = "Humidity";
            hum_graph_template.series[0].color = '#ff9933';
            $('#graph_hum').highcharts(hum_graph_template);

            $("#panel-water").trigger('click');

        });
    });
</script>