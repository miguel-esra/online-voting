<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>


<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Participantes</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Participantes
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box mb-30">
    <div class="pd-20">
        <h4 class="text-blue h4">Participantes</h4>
    </div>
    <div class="pb-20" style="width: 98%; margin: auto;">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <table class="data-table table stripe hover nowrap dataTable no-footer dtr-inline" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                        <thead>
                            <tr role="row">
                                <th scope="col" width="8%">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">DNI</th>
                                <th scope="col" width="14%">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('stylesheets') ?>

<link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.deskapp.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.deskapp.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/style.deskapp.css">

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>

<script src="/backend/src/plugins/datatables/js/jquery.dataTables.deskapp.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.deskapp.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.deskapp.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.deskapp.min.js"></script>

<script>

var participants_DT = $('#DataTables_Table_0').DataTable({
    processing: true,
    serverSide: true,
    scrollCollapse: true,
    autoWidth: false,
    responsive: true,
    dom : "<'row'<'col-md-3'f>><'row'<'col-lg-12't>><'row dom-bottom'<'col-md-6'i><'col-md-6'p>>",
    lengthChange: false,
    language: {
        "processing": "Procesando...",
        "lengthMenu": "Mostrar _MENU_ registros",
        "zeroRecords": "No se encontraron resultados",
        "emptyTable": "Ningún dato disponible en esta tabla",
        "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "infoFiltered": '<text class="text-info-filtered">(filtrado de un total de _MAX_ registros)</text>',
        "search": "Buscar:",
        "loadingRecords": "Cargando...",
        "aria": {
            "sortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "info": 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        "paginate": {
            "next": '<i class="ion-chevron-right"></i>',
            "previous": '<i class="ion-chevron-left"></i>'  
        }
    },
    ajax: "<?= route_to('get-participants') ?>",
    columnDefs: [
        { orderable: false, targets: [3] }
        // { visible: false, targets: 3 }
    ],
    order: [[0, 'desc']]
});

</script>

<?= $this->endSection() ?>
