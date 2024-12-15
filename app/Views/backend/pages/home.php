<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Votación</h4>
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
        <div class="col-md-4 text-center">
            <img src="/backend/vendors/images/banner-img.png" alt="">
        </div>
        <div class="col-md-8">
            <h4 class="font-20 weight-500 mb-10" style="padding-top: 1em;">
                Bienvenido(a)
                <div class="weight-600 font-30 text-blue">Elecciones de la Junta Directiva del Sindicato de La Libertad 2025-2026</div>
            </h4>
            <p class="font-18 max-width-1000">
                Por favor, lea la guía electoral que detalla cómo emitir su voto. Para ver la guía, hacer click aquí.
                <br>Tiene derecho a emitir un solo voto.
                <br>Seleccione el candidato de su elección de la lista que aparece a continuación.
            </p>
        </div>
    </div>
</div>

<?php if (!empty(get_voter_choice())) : ?>
<div class="row clearfix">
    <div class="col-md-12 col-sm-12 mb-30">
        <div class="card text-white bg-success card-box">
            <div class="card-header">¡Felicidades!</div>
            <div class="card-body">
                <h5 class="card-title text-white">Voto registrado correctamente</h5>
                <p class="card-text">
                    Para mayor información sobre tu voto, ingresa al enlace "<a class="card-text vote-href" href="<?= route_to('user.my.vote') ?>">Mi Voto</a>".
                </p>
            </div>
        </div>
    </div>
</div>
<?php else : ?>
<div class="row" style="justify-content: center;">
    <!-- Candidate 1 -->
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
        <div class="card-box" style="padding: 2.5em 0.2em 2em 0.2em;">
            <div class="text-center" style="user-select: none;">
                <input type="radio" name="radio_candidate" id="radio_candidate_1" class="hide-box" value="1">
                <label for="radio_candidate_1" class="label-radio">
                    <div class="profile-photo">
                        <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('user_profile_file_1').click();" class="edit-avatar" style="display: none;"><i class="fa fa-pencil"></i></a>
                        <input type="file" name="user_profile_file_1" id="user_profile_file_1" class="d-none" style="opacity: 0;">
                        <?php if (!empty(get_voter())) : ?>
                            <img src="/images/users/vote-icon.png" alt="" class="avatar-photo ci-avatar-photo" />
                        <?php endif; ?>
                    </div>
                    <h5 class="text-center h5 mb-0 ci-user-name">
                        <?php if (!empty(get_voter())) : ?>    
                            <?=  get_candidates()[0]->name ?>
                        <?php endif; ?>
                    </h5>
                    <p class="text-center text-muted font-14 ci-user-number">
                        <?php if (!empty(get_voter())) : ?>    
                            <?= 'Lista N° 1 Inscrita' ?>
                        <?php endif; ?>
                    </p>
                </label>
                <div style="padding: 2em 1em 1em 1em;">
                    <a href="" class="btn btn-info btn-sm candidate_details_btn" role="button" data-candidate="<?= get_candidates()[0]->candidate_number ?>" style="padding: 0.8em 1em; font-size: 12pt;">
                        <i class="icon-copy fa fa-eye" aria-hidden="true"></i>&ensp;Ver integrantes
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Candidate 2 -->
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
        <div class="card-box" style="padding: 2.5em 0.2em 2em 0.2em;">
            <div class="text-center" style="user-select: none;">
                <input type="radio" name="radio_candidate" id="radio_candidate_2" class="hide-box" value="2">
                <label for="radio_candidate_2" class="label-radio">
                    <div class="profile-photo">
                        <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('user_profile_file_2').click();" class="edit-avatar" style="display: none;"><i class="fa fa-pencil"></i></a>
                        <input type="file" name="user_profile_file_2" id="user_profile_file_2" class="d-none" style="opacity: 0;">
                        <?php if (!empty(get_voter())) : ?>
                            <img src="/images/users/vote-icon.png" alt="" class="avatar-photo ci-avatar-photo" />
                        <?php endif; ?>
                    </div>
                    <h5 class="text-center h5 mb-0 ci-user-name">
                        <?php if (!empty(get_voter())) : ?>    
                            <?= get_candidates()[1]->name ?>
                        <?php endif; ?>
                    </h5>
                    <p class="text-center text-muted font-14 ci-user-number">
                        <?php if (!empty(get_voter())) : ?>    
                            <?= 'Lista N° 2 Inscrita' ?>
                        <?php endif; ?>
                    </p>
                </label>
                <div style="padding: 2em 1em 1em 1em;">
                    <a href="" class="btn btn-info btn-sm candidate_details_btn" role="button" data-candidate="<?= get_candidates()[1]->candidate_number ?>" style="padding: 0.8em 1em; font-size: 12pt;">
                        <i class="icon-copy fa fa-eye" aria-hidden="true"></i>&ensp;Ver integrantes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Candidate 3 -->
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-30">
        <div class="card-box" style="padding: 2.5em 0.2em 2em 0.2em;">
            <div class="text-center" style="user-select: none;">
                <input type="radio" name="radio_candidate" id="radio_candidate_3" class="hide-box" value="3">
                <label for="radio_candidate_3" class="label-radio">
                    <div class="profile-photo">
                        <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('user_profile_file_3').click();" class="edit-avatar" style="display: none;"><i class="fa fa-pencil"></i></a>
                        <input type="file" name="user_profile_file_3" id="user_profile_file_3" class="d-none" style="opacity: 0;">
                        <?php if (!empty(get_voter())) : ?>
                            <img src="/images/users/vote-icon.png" alt="" class="avatar-photo ci-avatar-photo" />
                        <?php endif; ?>
                    </div>
                    <h5 class="text-center h5 mb-0 ci-user-name">
                        <?php if (!empty(get_voter())) : ?>    
                            <?= get_candidates()[2]->name ?>
                        <?php endif; ?>
                    </h5>
                    <p class="text-center text-muted font-14 ci-user-number">
                        <?php if (!empty(get_voter())) : ?>    
                            <?= 'Voto en blanco' ?>
                        <?php endif; ?>
                    </p>
                </label>
                <div style="padding: 2em 1em 1em 1em;">
                    <button class="btn btn-info btn-sm candidate_blank_btn" data-candidate="<?= get_candidates()[2]->candidate_number ?>" style="padding: 0.8em 1em; font-size: 12pt; pointer-events: none;" disabled>
                        <i class="icon-copy fa fa-eye" aria-hidden="true"></i>&ensp;Ver integrantes
                    </button>
                </div>
            </div>
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
                <a href="" class="btn btn-primary" role="button" id="cast_vote_btn" style="font-size: 14pt; padding: 1em 2em 1em 2em;">Registrar Voto</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include('modals/candidate-details-modal-form.php') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
$(document).on('click', '.candidate_details_btn', function (e) {
    e.preventDefault();
    var modal = $('body').find('div#candidate-details-modal');
    var candidate = $(this).data("candidate");
    var modal_title = (candidate == 1) ? "<?= get_candidates()[0]->name ?>" : "<?= get_candidates()[1]->name ?>";
    var modal_text = (candidate == 1) ? "<?= get_candidates()[0]->bio ?>" : "<?= get_candidates()[1]->bio ?>";
    var array_text = modal_text.split('.');
    
    for (let index = 0; index < array_text.length; index++) {
        if (index == array_text.length - 1) continue;
        if (index % 2 == 0) array_text[index] = '<div><b>' + array_text[index] + '</b></div>';
        if (index % 2 != 0) array_text[index] = '<div style="padding-bottom: 0.6em;"><i>' + array_text[index] + '</i></div>';
    }

    // console.log(array_text);
    modal.find('.modal-title').html(modal_title);
    modal.find('.modal-body > p.description').html(array_text);
    modal.modal('show');
});

$(document).on('click', '#cast_vote_btn', function (e) {
    e.preventDefault();
    var candidate_number = $('input[name="radio_candidate"]:checked').val();
    var candidate_name = $('input[name="radio_candidate"]:checked').next().find('.ci-user-name').text();
    var url = "<?= route_to('user.add-vote') ?>";
    var urlRedirect = "<?= route_to('user.my.vote') ?>";
    if (candidate_number) {
        toastr.remove();
        swal.fire({
            title: '¿Estás seguro(a) de que quieres continuar?',
            html: 'Has elegido ' + '<b>' + candidate_name + '</b>',
            type: "warning",
            showCloseButton: false,
            showCancelButton: true,
            cancelButtonText: 'No',
            confirmButtonText: 'Sí',
            cancelButtonColor: '#D33',
            confirmButtonColor: '#3085D6',
            width: 360,
            allowOutsideClick: false
        }).then(function (result) {
            if (result.value) {
                $.post(url, { candidate_number: candidate_number }, function (response) {
                    if ( response.status == 1) {
                        swal.fire({
                            title: "¡Buen Trabajo!", 
                            html: "Voto registrado correctamente.", 
                            type: "success",
                            allowOutsideClick: false
                        }).then(function () {
                            window.location.href = urlRedirect; 
                        });
                    } else {
                        toastr.error(response.msg).css("width", "100%").css("font-size", "12pt");
                    }
                }, 'json');
            } else {
                swal.close();
            }
        })
    } else {
        toastr.remove();
        var error_message = 'Por favor seleccione un candidato.';
        toastr.error(error_message).css("width", "100%").css("font-size", "12pt");
    }
});
</script>

<?= $this->endSection() ?>
