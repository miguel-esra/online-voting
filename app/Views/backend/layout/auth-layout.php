<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title><?= isset($pageTitle) ? $pageTitle : 'New Page Title'; ?></title>

		<!-- Site favicon -->
		<link rel="apple-touch-icon" sizes="180x180" href="/images/blog/<?= get_settings()->blog_favicon ?>" />
		<link rel="icon" type="image/png" sizes="32x32" href="/images/blog/<?= get_settings()->blog_favicon ?>" />
		<link rel="icon" type="image/png" sizes="16x16" href="/images/blog/<?= get_settings()->blog_favicon ?>" />

		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

		<!-- Google Font -->
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
		
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/icon-font.min.css" />
		<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />

		<?= $this->renderSection('stylesheets') ?>
		<style>
			/* Chrome */
			input::-webkit-inner-spin-button,
			input::-webkit-outer-spin-button {
				-webkit-appearance: none;
				margin: 0;
			}
			.div-login-title {
				display: flex;
				justify-content: center;
			}
			.h2-login-title {
				width: 100%;
			}
			@media only screen and (min-width: 1024px) {
				.h2-login-title {
					width: 60%;
				}
			}
			@media only screen and (max-width: 1024px) {
				.h2-login-title {
					padding: 0em 1em 1em 1em;
				}
			}
		</style>
	</head>
	<body class="login-page">
		<div class="login-header box-shadow">
			<div class="container-fluid d-flex justify-content-between align-items-center">
				<div class="brand-logo">
					<?php if ( ('/' . uri_string()) == route_to('admin.login.form') ) : ?>
					<a href="<?= route_to('admin.login.form') ?>">
					<?php else : ?>
					<a href="<?= route_to('user.login.form') ?>">
					<?php endif; ?>
						<img src="/images/blog/<?= get_settings()->blog_logo ?>" alt="" />
					</a>
				</div>
				<div class="login-menu">
					
				</div>
			</div>
		</div>
		<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
			<div class="container">
				<div class="div-login-title">
					<h2 class="text-center text-primary h2-login-title">Elecciones de la Junta Directiva del Sindicato de La Libertad 2025-2026</h2>
				</div>
				<div class="row align-items-center">
					<div class="col-md-6 col-lg-7" style="padding-bottom: 1em;">
						<img src="/backend/vendors/images/login-page-img.png" alt="" />
					</div>
					<div class="col-md-6 col-lg-5">
						
                        <?= $this->renderSection('content') ?>

					</div>
				</div>
			</div>
		</div>
		
		<!-- js -->
		<script src="/backend/vendors/scripts/core.js"></script>
		<script src="/backend/vendors/scripts/script.min.js"></script>
		<script src="/backend/vendors/scripts/process.js"></script>
		<script src="/backend/vendors/scripts/layout-settings.js"></script>

		<?= $this->renderSection('scripts') ?>

	</body>
</html>
