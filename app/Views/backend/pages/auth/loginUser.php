<?= $this->extend('backend/layout/auth-layout') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Identificación</h2>
    </div>

    <?php $validation = \Config\Services::validation(); ?>

    <form action="<?= route_to('user.login.handler') ?>" method="POST">

        <?= csrf_field() ?>

        <?php if(!empty(session()->getFlashdata('success'))) : ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif;?>
        <?php if(!empty(session()->getFlashdata('fail'))) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('fail') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif;?>
        
        <div class="input-group custom">
            <input type="text" class="form-control form-control-lg" placeholder="Correo electrónico" name="email" value="<?= set_value('email') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-email-1"></i></span>
            </div>
        </div>
        <?php if($validation->getError('email')) : ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= $validation->getError('email') ?>
            </div>
        <?php endif; ?>
        
        <div class="input-group custom">
            <input type="text" oninput="this.value = this.value.toUpperCase()" class="form-control form-control-lg" placeholder="Nombre del Padre o Madre" name="parent_name" value="<?= set_value('parent_name') ?>" autocomplete="off">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-user1"></i></span>
            </div>
        </div>
        <?php if($validation->getError('parent_name')) : ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= $validation->getError('parent_name') ?>
            </div>
        <?php endif; ?>

        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" placeholder="Últimos 4 dígitos del DNI" name="user_id" value="<?= set_value('user_id') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-padlock1"></i></span>
            </div>
        </div>
        <?php if($validation->getError('user_id')) : ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= $validation->getError('user_id') ?>
            </div>
        <?php endif; ?>
        

        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
            
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Ingresar">
               
                </div>
            </div>
        </div>

    </form>
</div>

<?= $this->endSection() ?>
