<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Ajustes</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('user.home') ?>">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Ajustes
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-4">
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#general_settings" role="tab" aria-selected="true">Ajustes Generales</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">Ícono y Logo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#social_media" role="tab" aria-selected="false">Redes Sociales</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="general_settings" role="tabpanel">
                <div class="pd-20">
                    <form action="<?= route_to('') ?>" method="POST" id="general_settings_form">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nombre del Sitio</label>
                                    <input type="text" class="form-control" name="blog_title" placeholder="Enter blog title" value="<?= get_settings()->blog_title ?>" readonly>
                                    <span class="text-danger error-text blog_title_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Correo Electrónico</label>
                                    <input type="text" class="form-control" name="blog_email" placeholder="Enter blog email" value="<?= get_settings()->blog_email ?>" readonly>
                                    <span class="text-danger error-text blog_email_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Teléfono</label>
                                    <input type="text" class="form-control" name="blog_phone" placeholder="Enter blog phone" value="<?= get_settings()->blog_phone ?>" readonly>
                                    <span class="text-danger error-text blog_phone_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Palabras Clave</label>
                                    <input type="text" class="form-control" name="blog_meta_keywords" placeholder="Enter blog meta keywords" value="<?= get_settings()->blog_meta_keywords ?>" readonly>
                                    <span class="text-danger error-text blog_meta_keywords_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Descripción</label>
                            <textarea name="blog_meta_description" id="" cols="4" rows="3" class="form-control" placeholder="Write blog meta description" readonly><?= get_settings()->blog_meta_description ?></textarea>
                            <span class="text-danger error-text blog_meta_description_error"></span>
                        </div>
                        <div class="form-group" style="display: none;">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="logo_favicon" role="tabpanel">
                <div class="pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Logo</h5>
                            <div class="mb2 mt-1" style="max-width: 352px; min-width: 302px;">
                                <img src="" alt="" class="img-thumbnail" id="logo-image-preview" data-ijabo-default-img="/images/blog/<?= get_settings()->blog_logo ?>">
                            </div>
                            <form action="<?= route_to('') ?>" method="POST" enctype="multipart/form-data" id="changeBlogLogoForm" style="display: none;">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                                <div class="mb-2">
                                    <input type="file" name="blog_logo" id="" class="form-control">
                                    <span class="text-danger error-text"></span>
                                </div>
                                <button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Cambiar logo</button>
                            </form>
                        </div>
    
                        <div class="col-md-6">
                            <h5>Ícono</h5>
                            <div class="mb2 mt-1" style="max-width: 148px; min-width: 98px;">
                                <img src="" alt="" class="img-thumbnail" id="favicon-image-preview" data-ijabo-default-img="/images/blog/<?= get_settings()->blog_favicon ?>">
                            </div>
                            <form action="<?= route_to('') ?>" method="POST" enctype="multipart/form-data" id="changeBlogFaviconForm" style="display: none;">
                                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                                <div class="mb-2">
                                    <input type="file" name="blog_favicon" id="" class="form-control">
                                    <span class="text-danger error-text"></span>
                                </div>
                                <button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Cambiar ícono</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="social_media" role="tabpanel">
                <div class="pd-20">
                    <form action="<?= route_to('') ?>" method="POST" id="social_media_form">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                        <div class="row">
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="">Facebook URL</label>
                                    <input type="text" class="form-control" name="facebook_url" placeholder="Enter facebook page URL" value="<?= get_social_media()->facebook_url ?>" readonly>
                                    <span class="text-danger error-text facebook_url_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="">Twitter URL</label>
                                    <input type="text" class="form-control" name="twitter_url" placeholder="Enter twitter page URL" value="<?= get_social_media()->twitter_url ?>" readonly>
                                    <span class="text-danger error-text twitter_url_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="">Instagram URL</label>
                                    <input type="text" class="form-control" name="instagram_url" placeholder="Enter instagram page URL" value="<?= get_social_media()->instagram_url ?>" readonly>
                                    <span class="text-danger error-text instagram_url_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="">YouTube URL</label>
                                    <input type="text" class="form-control" name="youtube_url" placeholder="Enter YouTube channel URL" value="<?= get_social_media()->youtube_url ?>" readonly>
                                    <span class="text-danger error-text youtube_url_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="">GitHub URL</label>
                                    <input type="text" class="form-control" name="github_url" placeholder="Enter GitHub URL" value="<?= get_social_media()->github_url ?>" readonly>
                                    <span class="text-danger error-text github_url_error"></span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label for="">LinkedIn URL</label>
                                    <input type="text" class="form-control" name="linkedin_url" placeholder="Enter LinkedIn URL" value="<?= get_social_media()->linkedin_url ?>" readonly>
                                    <span class="text-danger error-text linkedin_url_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="display: none;">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#general_settings_form').on('submit', function (e) {
        e.preventDefault();
        
        // CSRF Hash
        var csrfName = $('.ci_csrf_data').attr('name');     // CSRF token name
        var csrfHash = $('ci_csrf_data').val();     // CSRF hash
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
            beforeSend: function () {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function (response) {
                // Update CSRF hash
                $('.ci_csrf_data').val(response.token);

                if ( $.isEmptyObject(response.error) ) {
                    if ( response.status == 1 ) {
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function (prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        });
    });


    $('input[type="file"][name="blog_logo"]').ijaboViewer({
        preview: '#logo-image-preview',
        imageShape: 'rectangular',      // Square if is an avatar
        allowedExtensions: ['jpg','jpeg','png'], 
        onErrorShape: function (message, element) {
            toastr.error(message);
        },
        onInvalidType: function (message, element) {
            toastr.error(message);
        },
        onSuccess: function (message, element) {
            // Do Nothing
        }
    });


    $('#changeBlogLogoForm').on('submit', function (e) {
        e.preventDefault();
        var csrfName = $('ci_csrf_data').attr('name');      // CSRF token name
        var csrfHash = $('ci_csrf_data').val();     // CSRF hash
        var form = this;
        var formdata = new FormData(form);
            formdata.append(csrfName, csrfHash);

        var inputFileVal = $(form).find('input[type="file"][name="blog_logo"]').val();

        if ( inputFileVal.length > 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success: function (response) {
                    // Update CSRF hash
                    $('.ci_csrf_data').val(response.token);

                    if (response.status == 1) {
                        toastr.success(response.msg);
                        $(form)[0].reset();
                    } else {
                        toastr.error(response.msg);
                    }
                }
            });
        } else {
            $(form).find('span.error-text').text('Please select logo image file. PNG file type is recommended.');
        }
    });


    $('input[type="file"][name="blog_favicon"]').ijaboViewer({
        preview: '#favicon-image-preview',
        imageShape: 'square',      // Square if is an avatar
        allowedExtensions: ['png'], 
        onErrorShape: function (message, element) {
            toastr.error(message);
        },
        onInvalidType: function (message, element) {
            toastr.error(message);
        },
        onSuccess: function (message, element) {
            // Do Nothing
        }
    });


    $('#changeBlogFaviconForm').on('submit', function (e) {
        e.preventDefault();
        var csrfName = $('ci_csrf_data').attr('name');      // CSRF token name
        var csrfHash = $('ci_csrf_data').val();     // CSRF hash
        var form = this;
        var formdata = new FormData(form);
            formdata.append(csrfName, csrfHash);

        var inputFileVal = $(form).find('input[type="file"][name="blog_favicon"]').val();

        if ( inputFileVal.length > 0) {
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formdata,
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success: function (response) {
                    // Update CSRF hash
                    $('.ci_csrf_data').val(response.token);

                    if (response.status == 1) {
                        toastr.success(response.msg);
                        $(form)[0].reset();
                    } else {
                        toastr.error(response.msg);
                    }
                }
            });
        } else {
            $(form).find('span.error-text').text('Please select favicon image file. PNG file type is recommended.');
        }
    });


    $('#social_media_form').on('submit', function (e) {
        e.preventDefault();
        var csrfName = $('ci_csrf_data').attr('name');      // CSRF token name
        var csrfHash = $('ci_csrf_data').val();     // CSRF hash
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
            beforeSend: function () {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function (response) {
                // Update CSRF hash
                $('.ci_csrf_data').val(response.token);

                if ( $.isEmptyObject(response.error) ) {
                    if ( response.status == 1 ) {
                        toastr.success(response.msg);
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function (prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            }
        }); 
    });
</script>
<?= $this->endSection() ?>
