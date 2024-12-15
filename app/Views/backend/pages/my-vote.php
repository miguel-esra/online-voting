<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Mi Voto</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('user.home'); ?>">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Mi Voto
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<?php if ($voteSuccess) : ?>
<div class="row">
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-30">
        <div class="card-box pd-20 height-100-p mb-30">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h4 class="font-20 weight-500 mb-10" style="padding-top: 1em;">
                        Elecciones de la Junta Directiva del Sindicato de La Libertad 2025-2026
                        <div class="weight-600 font-24 text-blue">¡Muchas gracias por emitir tu voto!</div>
                    </h4>
                </div>
                <div class="col-md-12 text-center">
                    <img src="/backend/vendors/images/team-success.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <h4 class="font-20 weight-500 mb-10 text-center" style="padding-top: 1em;">
                        <div class="weight-600 font-24 text-blue">Voto Registrado</div>
                    </h4>
                </div>
            </div>
            <div class="profile-photo">
                <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('user_profile_file').click();" class="edit-avatar" style="display: none;"><i class="fa fa-pencil"></i></a>
                <input type="file" name="user_profile_file" id="user_profile_file" class="d-none" style="opacity: 0;">
                <?php if (!empty(get_voter())) : ?>
                    <img src="/images/users/vote-icon.png" alt="" class="avatar-photo ci-avatar-photo" />
                <?php else : ?>
                    <img src="<?= get_user()->picture == null ? '/images/users/vote-icon.png' : '/images/users/' . get_user()->picture ?>" alt="" class="avatar-photo ci-avatar-photo">
                <?php endif; ?> 
            </div>
            <h5 class="text-center h5 mb-0 ci-user-name">  
                <?= get_voter_choice_details()->name ?>
            </h5>
            <p class="text-center text-muted font-14 ci-user-number">
                <?php if ( get_voter_choice_details()->candidate_number == 1 ) : ?> 
                    Lista N° 1 Inscrita
                <?php elseif ( get_voter_choice_details()->candidate_number == 2 ) : ?> 
                    Lista N° 2 Inscrita
                <?php else : ?> 
                    Voto en blanco
                <?php endif; ?> 
            </p>
            <?php if ( get_voter_choice_details()->candidate_number == 3 ) : ?>
                <div style="display: none;"></div>
            <?php else : ?>
            <div style="padding: 1em 1em 1em 1em; display: flex; justify-content: center;">
                <button class="btn btn-info btn-sm candidate_details_btn" data-candidate="<?= get_voter_choice_details()->candidate_number ?>" style="padding: 0.8em 1em; font-size: 12pt;">
                    <i class="icon-copy fa fa-eye" aria-hidden="true"></i>&ensp;Ver integrantes
                </button>
            </div>
            <?php endif; ?> 
            <div class="wrap-svg text-center">
                <svg width="90px" height="90px" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="13.5 13.5 37 37" enable-background="new 0 0 64 64">
                    <g>
                        <circle id="circle-vote-svg" fill="#FFFFFF" stroke="#2BA50EFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" cx="32" cy="32" r="17.5"/>
                    </g>
                    <g>
                        <polyline id="check-vote-svg" fill="none" stroke="#2BA50EFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="21.5,32 28.5,39 42.5,25"/>
                    </g>
                </svg>
            </div>
        </div>
    </div>
</div>
<?php else : ?>
<div class="row clearfix">
    <div class="col-md-12 col-sm-12 mb-30">
        <div class="card text-white bg-info card-box">
            <div class="card-header">¡Advertencia!</div>
            <div class="card-body">
                <h5 class="card-title text-white">Aún no ha registrado su voto</h5>
                <p class="card-text">
                    Ingrese al enlace "<a class="card-text vote-href" href="<?= route_to('user.home') ?>">Votación</a>" para poder emitir su voto.
                </p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include('modals/candidate-details-modal-form.php') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// disable button if vote is blank
$(document).ready(function () {
    var btn_candidate = $('.candidate_details_btn');
    var candidate_number = btn_candidate.data('candidate');

    if (candidate_number == 3) {
        btn_candidate.prop( "disabled", true );
        btn_candidate.css( "pointer-events", "none" );
    }
});

$(document).on('click', '.candidate_details_btn', function (e) {
    e.preventDefault();
    var modal = $('body').find('div#candidate-details-modal');
    var candidate = $(this).data("candidate");
    // for blank vote
    if (candidate == 3) {
        return false;
    }
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
</script>
<?= $this->endSection() ?>
