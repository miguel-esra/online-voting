<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Perfil</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= (!empty(get_voter())) ? route_to('user.home') : route_to('admin.home') ?>">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Perfil
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('user_profile_file').click();" class="edit-avatar" style="display: none;"><i class="fa fa-pencil"></i></a>
                <input type="file" name="user_profile_file" id="user_profile_file" class="d-none" style="opacity: 0;">
                <?php if (!empty(get_voter())) : ?>
                    <img src="/images/users/default-avatar.png" alt="" class="avatar-photo ci-avatar-photo" />
                <?php else : ?>
                    <img src="<?= get_user()->picture == null ? '/images/users/default-avatar.png' : '/images/users/' . get_user()->picture ?>" alt="" class="avatar-photo ci-avatar-photo">
                <?php endif; ?> 
            </div>
            <h5 class="text-center h5 mb-0 ci-user-name">
                <?php if (!empty(get_voter())) : ?>    
                    <?= get_voter()->name ?>
                <?php else : ?>
                    <?= get_user()->name ?>
                <?php endif; ?>
            </h5>
            <p class="text-center text-muted font-14 ci-user-email">
                <?php if (!empty(get_voter())) : ?>    
                    <?= 'Votante' ?>
                <?php else : ?>
                    <?= get_user()->email ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
        <div class="card-box height-100-p overflow-hidden">
            <div class="profile-tab height-100-p">
                <div class="tab height-100-p">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#personal_details" role="tab">Datos Personales</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#change_password" role="tab">Cambiar Contraseña</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Personal Details Tab start -->
                        <div class="tab-pane fade show active" id="personal_details" role="tabpanel">
                            <div class="pd-20">

                                <form action="<?= route_to('update-personal-details'); ?>" method="POST" id="personal_details_form">

                                    <?= csrf_field(); ?>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Nombre</label>
                                                <?php if (!empty(get_voter())) : ?>    
                                                    <input type="text" name="name" class="form-control" placeholder="Enter full name" value="<?= get_voter()->name ?>" readonly>
                                                <?php else : ?>
                                                    <input type="text" name="name" class="form-control" placeholder="Enter full name" value="<?= get_user()->name ?>" readonly>
                                                <?php endif; ?>
                                                <span class="text-danger error-text name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <?php if (!empty(get_voter())) : ?>    
                                                <label for="">DNI</label>
                                                    <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?= get_voter()->user_id ?>" readonly>
                                                <?php else : ?>
                                                <label for="">Usuario</label>
                                                    <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?= get_user()->username ?>" readonly>
                                                <?php endif; ?>
                                                <span class="text-danger error-text username_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Biografía</label>
                                        <?php if (!empty(get_voter())) : ?>    
                                            <textarea name="bio" id="" cols="30" rows="10" class="form-control" placeholder="Bio..." readonly>&nbsp;</textarea>
                                        <?php else : ?>
                                            <textarea name="bio" id="" cols="30" rows="10" class="form-control" placeholder="Bio..." readonly><?= get_user()->bio ?></textarea>
                                        <?php endif; ?>
                                        <span class="text-danger error-text bio_error"></span>
                                    </div>
                                    <div class="form-group" style="display: none;">
                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                        <!-- Personal Details Tab End -->
                        <!-- Change Password Tab start -->
                        <div class="tab-pane fade" id="change_password" role="tabpanel">
                            <div class="pd-20 profile-task-wrap">
                                <form action="<?= route_to('change-password') ?>" method="POST" id="change_password_form">
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Contraseña actual</label>
                                                <input type="password" class="form-control" placeholder="Ingrese la contraseña actual" name="current_password" readonly>
                                                <span class="text-danger error-text current_password_error"></span>
                                            </div>
                                        </div>
                                        <!-- Empty div -->
                                        <div class="col-md-6"></div>
                                        <!-- End empty div -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Nueva contraseña</label>
                                                <input type="password" class="form-control" placeholder="Ingrese la nueva contraseña" name="new_password" readonly>
                                                <span class="text-danger error-text new_password_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Confirmar nueva contraseña</label>
                                                <input type="password" class="form-control" placeholder="Vuelva a escribir la nueva contraseña" name="confirm_new_password" readonly>
                                                <span class="text-danger error-text confirm_new_password_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" style="display: none;">Cambiar contraseña</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Change Password Tab End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    $('#personal_details_form').on('submit', function(e){
        e.preventDefault();
        var form = this;
        var formdata = new FormData(form);

        $.ajax({
            url:$(form).attr('action'),
            method:$(form).attr('method'),
            data:formdata,
            processData:false,
            dataType:'json',
            contentType:false,
            beforeSend:function(){
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success:function(response){
                if ( $.isEmptyObject(response.error) ) {
                    if ( response.status == 1 ) {
                        $('.ci-user-name').each(function(){
                            $(this).html(response.user_info.name);
                        });
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function(prefix, val){
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        });
    });


    $('#user_profile_file').ijaboCropTool({
        preview : '.ci-avatar-photo',
        setRatio: 1,
        allowedExtensions: ['jpg','jpeg','png'],
        processUrl: '<?= route_to('update-profile-picture') ?>',
        withCSRF: ['<?= csrf_token() ?>','<?= csrf_hash() ?>'],
        onSuccess: function(message, element, status){
            if ( status == 1 ) {
                toastr.success(message);
            } else {
                toastr.error(message);
            }
        },
        onError: function(message, element, status){
            // alert(message);
            toastr.error(message);
        }
    }); 

    // Change password
    $('#change_password_form').on('submit', function(e) {
        e.preventDefault();
       
        // CSRF Hash
        var csrfName = $('.ci_csrf_data').attr('name'); // CSRF token name
        var csrfHash = $('.ci_csrf_data').val();
        var form = this;
        var formdata = new FormData(form);
            formdata.append(csrfName, csrfHash);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formdata,
            processData: false,
            dataType: 'json',
            contentType: false,
            cache: false,
            beforeSend: function(){
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response){
                // Update CSRF hash
                $('.ci_csrf_data').val(response.token);

                if ( $.isEmptyObject(response.error) ) {
                    if ( response.status == 1 ) {
                        $(form)[0].reset();
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function(prefix, val){
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        });
        
    });
</script>

<?= $this->endSection() ?>
