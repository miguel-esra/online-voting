<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Resultados</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home'); ?>">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Resultados
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="bg-white pd-20 card-box mb-30">
    <div id="chart_1" style="height: 560px;"></div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="/extra-assets/highcharts-6.0.7/highcharts.js"></script>
<script src="/extra-assets/highcharts-6.0.7/highcharts-3d.js"></script>
<script src="/extra-assets/highcharts-6.0.7/highcharts-more.js"></script>

<script>
function showChart() {
    $(document).ready(function() {
        var receivedData; // store your value here
        var url = "<?= route_to('get-results') ?>";

        var functionGetData = function() {
            return $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                success: function(data) {
                    receivedData = data;
                    // console.log(receivedData);
                }
            });
        };

        functionGetData().done(createChart);
    });
}

function createChart(data) {
    var options = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Elecciones Municipales 2024',
            margin : 30,
            style: {
                fontSize: '26px'
            },
        },
        subtitle: {
            text: 'Resultados Votos Emitidos',
            style: {
                fontSize: '18px',
            }
        },
        xAxis: {
            categories: [''],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: '',
            labels: {
                useHTML: true,
                style: {
                    fontSize: '13px'
                }
            }
        },
        tooltip: {
            headerFormat:   '<span style="font-size:14px">{point.key}</span><table>',
            pointFormat:    '<tr><td style="color:{series.color};padding:0.5em 1em 0.5em 1em; font-size:14px; font-weight: 600;">{series.name}: </td>' +
                            '<td style="padding:0em 1em 0em 1em; text-align:right;"><b>{point.y:.0f}</b></td></tr>',
            footerFormat:   '</table>',
            shared: false,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.15,
                borderWidth: 0
            }
        },
        series: [

        ],
        credits: {
            enabled: false
        }
    };

    var colors = ['#9DBDFF', '#FF9874', '#29ADB2'];
    for (var i = 0; i <= data.length-1; i++)
    {
        var item = data[i];
        options.series.push({
            "type": "column",
            "name": item.name,
            "data": [parseInt(item.total)],
            "color": colors[i]
        });
    }

    $(document).ready(function() {
        var chart;
        chart = new Highcharts.chart('chart_1', options);
    });
}

showChart();

</script>

<?= $this->endSection() ?>