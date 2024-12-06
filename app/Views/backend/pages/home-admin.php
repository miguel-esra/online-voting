<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<?php use CodeIgniter\I18n\Time; ?>

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

<div class="row clearfix">
    <div class="col-md-12 col-sm-12 mb-20">
        <div class="card text-white bg-info card-box">
            <div class="card-header"><h5 class="text-white">Elecciones de la Junta Directiva del Sindicato de La Libertad 2025-2026</h5></div>
            <div class="card-body">
                <table class="table table-bordered table-results">
                    <tbody>
                        <tr>
                            <td scope="col" width="" rowspan="4" style="text-align: right; vertical-align: middle;">
                                <h1 class="text-white"><i class="icon-copy dw dw-edit-1"></i></h1>
                            </td>
                            <td scope="col" width="65%" id="td-width-results"><h6 class="text-white">ELECTORES HÁBILES: <?= number_format(count_voters(), 0, '', ',') ?></h6></td>
                        </tr>
                        <tr>
                            <td><h6 class="text-white">TOTAL DE VOTOS EMITIDOS: <?= number_format(count_votes(), 0, '', ',') ?></h6></td>
                        </tr>
                        <tr>
                            <td><h6 class="text-white">ACTUALIZADO EL <?= Time::now('America/Lima')->format('d/m/Y') ?> A LAS <?= Time::now('America/Lima')->format('H:i') ?></h6></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Total de Votos</h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table text-center">
            <tbody>
                <tr>
                    <th scope="col">LISTAS INSCRITAS</th>
                    <th scope="col">TOTAL</th>
                    <th scope="col">% VÁLIDOS</th>
                    <th scope="col">% EMITIDOS</th>
                </tr>
                <tr>
                    <?php if ( get_votes_candidates()[0]->votes != 0 ) : ?>
                    <td scope="row"><?= get_votes_candidates()[0]->name ?></td>
                    <td><?= number_format(get_votes_candidates()[0]->votes, 0, '', ',') ?></td>
                    <td><?= number_format(get_votes_candidates()[0]->votes / (get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes) * 100, 3, '.', '') ?> %</td>
                    <td><?= number_format(get_votes_candidates()[0]->votes / count_votes() * 100, 3, '.', '') ?> %</td>
                    <?php else : ?>
                    <td scope="row"><?= get_candidates()[0]->name ?></td>
                    <td>0</td>
                    <td><?= number_format(0, 3, '.', '') ?> %</td>
                    <td><?= number_format(0, 3, '.', '') ?> %</td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <?php if ( get_votes_candidates()[0]->votes != 0 ) : ?>
                    <td scope="row"><?= get_votes_candidates()[1]->name ?></td>
                    <td><?= number_format(get_votes_candidates()[1]->votes, 0, '', ',') ?></td>
                    <td><?= number_format(get_votes_candidates()[1]->votes / (get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes) * 100, 3, '.', '') ?> %</td>
                    <td><?= number_format(get_votes_candidates()[1]->votes / count_votes() * 100, 3, '.', '') ?> %</td>
                    <?php else : ?>
                    <td scope="row"><?= get_candidates()[1]->name ?></td>
                    <td>0</td>
                    <td><?= number_format(0, 3, '.', '') ?> %</td>
                    <td><?= number_format(0, 3, '.', '') ?> %</td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td scope="row">TOTAL DE VOTOS VÁLIDOS</td>
                    <?php if ( get_votes_candidates()[0]->votes != 0 ) : ?>
                    <td><?= number_format(get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes, 0, '', ',') ?></td>
                    <td><?= number_format((get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes) / (get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes) * 100, 3, '.', '') ?> %</td>
                    <td><?= number_format((get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes) / count_votes() * 100, 3, '.', '') ?> %</td>
                    <?php else : ?>
                    <td>0</td>
                    <td><?= number_format(0, 3, '.', '') ?> %</td>
                    <td><?= number_format(0, 3, '.', '') ?> %</td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td scope="row">VOTOS EN BLANCO</td>
                    <td><?= number_format(get_votes_blank(), 0, '', ',') ?></td>
                    <td></td>
                    <?php if ( !empty(count_votes()) ) : ?>
                    <td><?= number_format(get_votes_blank() / count_votes() * 100, 3, '.', '') ?> %</td>
                    <?php else : ?>
                    <td><?= number_format(0, 3, '.', '') ?> %</td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td scope="row">TOTAL DE VOTOS EMITIDOS</td>
                    <td><?= number_format(count_votes(), 0, '', ',') ?></td>
                    <td></td>
                    <?php if ( !empty(count_votes()) ) : ?>
                    <td><?= number_format((get_votes_candidates()[0]->votes + get_votes_candidates()[1]->votes + get_votes_blank()) / count_votes() * 100, 3, '.', '') ?> %</td>
                    <?php else : ?>
                    <td><?= number_format(0, 3, '.', '') ?> %</td>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="pd-20 card-box mb-30">
    <div class="clearfix mb-20">
        <div class="pull-left">
            <h4 class="text-blue h4">Participación</h4>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table text-center">
            <tbody>
                <tr>
                    <th scope="col" colspan="4">ELECTORES HÁBILES</th>
                </tr>
                <tr>
                    <td scope="row" colspan="4"><?= number_format(count_voters(), 0, '', ',') ?></td>
                </tr>
                <tr>
                    <th scope="col" colspan="2">PARTICIPACIÓN</th>
                    <th scope="col" colspan="2">AUSENTISMO</th>
                </tr>
                <tr>
                    <td scope="row">TOTAL ASISTENTES</td>
                    <td>% TOTAL ASISTENTES</td>
                    <td>TOTAL AUSENTES</td>
                    <td>% TOTAL AUSENTES</td>
                </tr>
                <tr>
                    <td scope="row"><?= number_format(count_votes(), 0, '', ',') ?></td>
                    <td><?= number_format(count_votes() / count_voters() * 100, 3, '.', '') ?> %</td>
                    <td><?= number_format(count_voters() - count_votes(), 0, '', ',') ?></td>
                    <td><?= number_format((count_voters() - count_votes()) / count_voters() * 100, 3, '.', '') ?> %</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-md-6 mb-20">
        <div class="card-box height-100-p pd-20">
            <div id="chart_bar_1" style="height: 560px;"></div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 mb-20">
        <div class="card-box height-100-p pd-20">
            <div id="chart_pie_1" style="height: 560px;"></div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="/extra-assets/highcharts-6.0.7/highcharts.js"></script>
<script src="/extra-assets/highcharts-6.0.7/highcharts-3d.js"></script>
<script src="/extra-assets/highcharts-6.0.7/highcharts-more.js"></script>

<script>
function showCharts() {
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

        functionGetData().done(createChartBar);
        functionGetData().done(createChartPie);
    });
}

function createChartBar(data) {
    var options = {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Número de Votos Obtenidos',
            margin : 30,
            style: {
                fontSize: '22px'
            },
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
        chart = new Highcharts.chart('chart_bar_1', options);
    });
}

function createChartPie(data) {
    var options = {
        title: {
            text: 'Porcentaje de Votos Obtenidos (%)',
            margin : 30,
            style: {
                fontSize: '22px'
            },
        },
        xAxis: {
            categories: ['']
        },
        tooltip: {
            headerFormat:   '<span style="color:{point.color}; font-size:14px; font-weight: 600; padding:0.5em 1em 0.5em 1em;">{point.key}</span><table>',
            pointFormat:    '<tr><td style="padding:0.5em 1em 0.5em 1em; font-size:14px; font-weight: 600;">Porcentaje: </td>' +
                            '<td style="padding:0em 1em 0em 1em; text-align:right;"><b>{point.percentage:.3f} %</b></td></tr>',
            footerFormat:   '</table>',
            shared: false,
            useHTML: true
        },
        plotOptions: {
            pie: {
                size: 300,
            }
        },
        chart: {
            events: {
                load: function() {
                    this.series[0].points.forEach(point => {
                        point.update({
                            dataLabels: {
                                style: {
                                    width: `140px`
                                }
                            }
                        })
                    })
                }
            }
        },
        series: [

        ],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 440
                },
                chartOptions: {
                    plotOptions: {
                        pie: {
                            size: 180,
                            dataLabels: {
                                distance: 15
                            }
                        }
                    }
                }
            }]
		},
        credits: {
            enabled: false
        }
    };

    var colors = ['#9DBDFF', '#FF9874', '#29ADB2'];
    var dataPie = [];

    for (var i = 0; i <= data.length-1; i++)
    {
        var item = data[i];
        dataPie.push({
            "name": item.name,
            "y": parseInt(item.total),
            "selected": i == 0 ? true : false,
            "sliced": i == 0 ? true : false,
            "color" : colors[i]
        });
    }

    options.series.push({
        "type": 'pie',
        "allowPointSelect": true,
        "keys": ['name', 'y', 'selected', 'sliced'],
        "data": dataPie,
        "showInLegend": true
    });

    $(document).ready(function() {
        var chart;
        var w = $('#chart_pie_1').closest("row").width();
        chart = new Highcharts.chart('chart_pie_1', options);
    });
}

showCharts();

</script>

<?= $this->endSection() ?>
