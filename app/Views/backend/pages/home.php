<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Inicio</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('user.home'); ?>">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Votación
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box pd-20 height-100-p mb-30">
    <div class="row align-items-center">
        <div class="col-md-4">
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

<div class="row">
    <!-- Candidate 1 -->
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('user_profile_file_1').click();" class="edit-avatar" style="display: none;"><i class="fa fa-pencil"></i></a>
                <input type="file" name="user_profile_file_1" id="user_profile_file_1" class="d-none" style="opacity: 0;">
                <?php if (!empty(get_voter())) : ?>
                    <img src="/images/users/default-avatar.png" alt="" class="avatar-photo ci-avatar-photo" />
                <?php endif; ?>
            </div>
            <h5 class="text-center h5 mb-0 ci-user-name">
                <?php if (!empty(get_voter())) : ?>    
                    <?=  get_candidates()[0]->name ?>
                <?php endif; ?>
            </h5>
            <p class="text-center text-muted font-14 ci-user-number">
                <?php if (!empty(get_voter())) : ?>    
                    <?= 'Candidato ' . get_candidates()[0]->candidate_number ?>
                <?php endif; ?>
            </p>
            <p class="text-center text-muted font-italic font-14 ci-user-bio">
                <?php if (!empty(get_voter())) : ?>    
                    <?=  str_replace(".", "<br>", get_candidates()[0]->bio) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    
    <!-- Candidate 2 -->
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('user_profile_file_2').click();" class="edit-avatar" style="display: none;"><i class="fa fa-pencil"></i></a>
                <input type="file" name="user_profile_file_2" id="user_profile_file_2" class="d-none" style="opacity: 0;">
                <?php if (!empty(get_voter())) : ?>
                    <img src="/images/users/default-avatar.png" alt="" class="avatar-photo ci-avatar-photo" />
                <?php endif; ?>
            </div>
            <h5 class="text-center h5 mb-0 ci-user-name">
                <?php if (!empty(get_voter())) : ?>    
                    <?= get_candidates()[1]->name ?>
                <?php endif; ?>
            </h5>
            <p class="text-center text-muted font-14 ci-user-number">
                <?php if (!empty(get_voter())) : ?>    
                    <?= 'Candidato ' . get_candidates()[1]->candidate_number ?>
                <?php endif; ?>
            </p>
            <p class="text-center text-muted font-italic font-14 ci-user-bio">
                <?php if (!empty(get_voter())) : ?>    
                    <?=  str_replace(".", "<br>", get_candidates()[1]->bio) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>

    <!-- Candidate 3 -->
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('user_profile_file_3').click();" class="edit-avatar" style="display: none;"><i class="fa fa-pencil"></i></a>
                <input type="file" name="user_profile_file_3" id="user_profile_file_3" class="d-none" style="opacity: 0;">
                <?php if (!empty(get_voter())) : ?>
                    <img src="/images/users/default-avatar.png" alt="" class="avatar-photo ci-avatar-photo" />
                <?php endif; ?>
            </div>
            <h5 class="text-center h5 mb-0 ci-user-name">
                <?php if (!empty(get_voter())) : ?>    
                    <?= get_candidates()[2]->name ?>
                <?php endif; ?>
            </h5>
            <p class="text-center text-muted font-14 ci-user-number">
                <?php if (!empty(get_voter())) : ?>    
                    <?= 'Candidato ' . get_candidates()[2]->candidate_number ?>
                <?php endif; ?>
            </p>
            <p class="text-center text-muted font-italic font-14 ci-user-bio">
                <?php if (!empty(get_voter())) : ?>    
                    <?=  str_replace(".", "<br>", get_candidates()[2]->bio) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-sm-12 col-md-12 mb-30">
        <div class="card card-box text-center">
            <div class="card-body">
                <p class="card-text">
                    Verifique su elección antes de continuar.
                </p>
                <a href="" class="btn btn-primary" role="button" id="cast_vote_btn">Registrar Voto</a>
            </div>
        </div>
    </div>
</div>

<?php include('modals/confirmation-modal-form.php') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).on('click', '#cast_vote_btn', function (e) {
        e.preventDefault();
        var modal = $('body').find('div#confirmation-modal');
        modal.modal('show');
    });

    $(document).on('click', '#confirm_vote_btn', function (e) {
        e.preventDefault();
        var modal = $('body').find('div#confirmation-modal');
        modal.modal('hide');
        swal.fire({
                title: '¡Buen trabajo!',
                text: 'Voto registrado correctamente.',
                type: 'success'
        });
    });
</script>
<?= $this->endSection() ?>
