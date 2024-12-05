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
		<link rel="stylesheet" href="/extra-assets/ijabo/ijabo.min.css" />
		<link rel="stylesheet" href="/extra-assets/ijaboCropTool/ijaboCropTool.min.css" />
        
        <?= $this->renderSection('stylesheets') ?>
		<style>
			.swal2-popup{
				font-size: .92em;
			}
			.swal2-title {
				font-size: 24px !important;
				padding-bottom: 0.3em !important;
			}
			.hide-box{
				display: none;
			}
			.label-radio{
				text-align: center;
				box-shadow: 0px 4px 14px -4px rgba(0, 0, 0, 0.35);
				padding: 1em 1.3em 1em 1.3em;
				border: 3px solid #fff;
				cursor: pointer;
				transition: all 0.3s ease-in;
				border-radius: 40px;
			}
			input[type=radio]:checked + .label-radio{
				border-color: #8FD14F;
				border: 3px solid #8FD14F;
			}
			body #toast-container > div {
				opacity: 0.96;
				margin-top: 5em;
			}
			.vote-href {
				color: white;
			}
			.vote-href:hover {
				color: white;
				font-weight: 600;
			}
			.wrap-svg {
				margin: 30px;
			}
			#circle-vote-svg {
				stroke-dashoffset: 1000;
				stroke-dasharray: 1000;
				animation-name: success-svg;
				animation-duration: 4s;
				animation-timing-function: ease-in-out;
				animation-fill-mode: forwards;
			}
			#check-vote-svg {
				stroke-dashoffset: 1000;
				stroke-dasharray: 1000;
				animation-name: success-svg;
				animation-duration: 4s;
				animation-timing-function: ease-in-out;
				animation-fill-mode: forwards;
				animation-delay: 1s;
			}
			@keyframes success-svg {
				to {
					stroke-dashoffset: 0;
				}
			}
			@media only screen and (min-width: 768px) {
				.dataTables_filter {
					float: left !important;
				}
				.dataTables_filter input[type="search"] {
					width: 500px !important;
				}
			}
			@media only screen and (max-width: 767px) {
				.text-info-filtered {
					display: block !important;
				}
				.dom-bottom {
					line-height: 1.5 !important;
				}
				.pagination {
					padding-top: 1em !important;
					justify-content: center !important;
				}
				#td-width-results {
					width: 80% !important;
				}
			}
			.data-table td {
				padding-top: 0.95em !important;
				padding-bottom: 0.95em !important;
			}
			.table-results, .table-results th .table-results tr, .table-results td {
				border: none !important;
				padding: 0.5em 1em 0.5em 1em !important;
				margin: 0em !important;
				background: none !important;
			}
		</style>
    </head>
	<body>
		
        <?php include('inc/header.php') ?>

        <?php include('inc/right-sidebar.php') ?>
		
		<?php include('inc/left-sidebar.php') ?>

		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="pd-ltr-20 xs-pd-20-10">
				<div class="min-height-200px">
					
					<div>
                        <?= $this->renderSection('content') ?>
                    </div>

				</div>
                
                <?php include('inc/footer.php') ?>
			</div>
		</div>
		
		<!-- js -->
		<script src="/backend/vendors/scripts/core.js"></script>
		<script src="/backend/vendors/scripts/script.min.js"></script>
		<script src="/backend/vendors/scripts/process.js"></script>
		<script src="/backend/vendors/scripts/layout-settings.js"></script>
		<script src="/extra-assets/ijabo/ijabo.min.js"></script>
		<script src="/extra-assets/ijabo/jquery.ijaboViewer.min.js"></script>
		<script src="/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>

		<?= $this->renderSection('scripts') ?>

	</body>
</html>
