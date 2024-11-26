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

<div class="card-box pd-20 height-100-p mb-30">
    <div class="row align-items-center">
        <div class="col-md-4 text-center">
            <img src="/backend/vendors/images/banner-img.png" alt="">
        </div>
        <div class="col-md-8">
            <h4 class="font-20 weight-500 mb-10" style="padding-top: 1em;">
                Bienvenido(a)
                <div class="weight-600 font-30 text-blue">Elecciones Municipales 2024</div>
            </h4>
            <p class="font-18 max-width-1000">
                Por favor, lea la guía electoral que detalla cómo emitir su voto. Para ver la guía, hacer click aquí.
                <br>Tiene derecho a emitir un solo voto.
                <br>Seleccione el candidato de su elección de la lista que aparece a continuación.
            </p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    
</script>
<?= $this->endSection() ?>
