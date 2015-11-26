<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" ng-app="app">
<head>
	<!-- ENVIRONMENT :: <?= $_ENV['ENVIRONMENT'] ?> -->
	<meta charset="<?php bloginfo('charset'); ?>">
	
	<!-- DNS Prefetch -->
	<link rel="dns-prefetch" href="//www.google-analytics.com">
	
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
	
	<!-- Meta -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		
	<!-- CSS + jQuery + JavaScript -->
	<?php wp_head(); ?>
	
</head>
<body <?php body_class(''); ?> ng-controller="global">
	
	<header class="row-fluid">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" ng-click="mobileCollapse = !mobileCollapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/">
						Brand
					</a>
				</div>
				
				<div class="collapse navbar-collapse" collapse="mobileCollapse">
					<?= fw\nav() ?>
				</div>
			</div><!-- /.container-fluid -->
		</nav>
	</header>