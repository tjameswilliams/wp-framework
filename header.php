<!DOCTYPE html>
<html <?php language_attributes(); ?> ng-app="app">
<head>
	<!-- ENVIRONMENT :: <?= $_ENV['ENVIRONMENT'] ?> -->
	<meta charset="<?php bloginfo('charset'); ?>">
	
	<!-- DNS Prefetch -->
	<link rel="dns-prefetch" href="//www.google-analytics.com">
	
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
	
	<!-- Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		
	<!-- CSS + jQuery + JavaScript -->
	<?php wp_head(); ?>
	
</head>
<body <?php body_class(''); ?> ng-controller="global" ng-cloak>
	<div layout="column" tabindex="-1" role="main" flex>
		<md-toolbar>
			<div class="md-toolbar-tools">
				<md-button class="md-icon-button hide-gt-sm" aria-label="Settings">
					<md-icon md-svg-icon="<?= get_template_directory_uri() ?>/node_modules/material-design-icons/navigation/svg/production/ic_menu_24px.svg"></md-icon>
				</md-button>
				<span class="site-name">Some Site!</span>
				<span flex></span>
				<? $nav = fw\nav();
				if( is_string($nav) ) :
					print $nav;
				else : 
					foreach( $nav as $menu_item ) : 
						if( !empty($menu_item['children'])) : ?>
						<md-menu md-position-mode="target-right target">
							<? include TEMPLATEPATH.'/views/menu-item.tpl' ?>
							<md-menu-content width="4">
								<? foreach($menu_item['children'] as $menu_item) : ?>
									<md-menu-item>
										<? include TEMPLATEPATH.'/views/menu-item.tpl' ?>
									</md-menu-item>
								<? endforeach ?>
							</md-menu-content>
						</md-menu>
						<? else : ?>
							<? include TEMPLATEPATH.'/views/menu-item.tpl' ?>
						<? endif ?>
					<? endforeach ?>
				<? endif ?>
		
			</div><!-- /.header -->
		</md-toolbar>
			
		<md-content flex layout-padding md-scroll-y>
		    <p>Lorem ipsum dolor sit amet, ne quod novum mei. Sea omnium invenire mediocrem at, in lobortis conclusionemque nam. Ne deleniti appetere reprimique pro, inani labitur disputationi te sed. At vix sale omnesque, id pro labitur reformidans accommodare, cum labores honestatis eu. Nec quem lucilius in, eam praesent reformidans no. Sed laudem aliquam ne.</p>
		    <p>
		Facete delenit argumentum cum at. Pro rebum nostrum contentiones ad. Mel exerci tritani maiorum at, mea te audire phaedrum, mel et nibh aliquam. Malis causae equidem vel eu. Noster melius vis ea, duis alterum oporteat ea sea. Per cu vide munere fierent.
		    </p>
		    <p>
		Ad sea dolor accusata consequuntur. Sit facete convenire reprehendunt et. Usu cu nonumy dissentiet, mei choro omnes fuisset ad. Te qui docendi accusam efficiantur, doming noster prodesset eam ei. In vel posse movet, ut convenire referrentur eum, ceteros singulis intellegam eu sit.
		    </p>
		    <p>
		Sit saepe quaestio reprimique id, duo no congue nominati, cum id nobis facilisi. No est laoreet dissentias, idque consectetuer eam id. Clita possim assueverit cu his, solum virtute recteque et cum. Vel cu luptatum signiferumque, mel eu brute nostro senserit. Blandit euripidis consequat ex mei, atqui torquatos id cum, meliore luptatum ut usu. Cu zril perpetua gubergren pri. Accusamus rationibus instructior ei pro, eu nullam principes qui, reque justo omnes et quo.
		    </p>
		    <p>
		Sint unum eam id. At sit fastidii theophrastus, mutat senserit repudiare et has. Atqui appareat repudiare ad nam, et ius alii incorrupte. Alii nullam libris his ei, meis aeterno at eum. Ne aeque tincidunt duo. In audire malorum mel, tamquam efficiantur has te.
		    </p>
		    <p>
		Qui utamur tacimates quaestio ad, quod graece omnium ius ut. Pri ut vero debitis interpretaris, qui cu mentitum adipiscing disputationi. Voluptatum mediocritatem quo ut. Fabulas dolorem ei has, quem molestie persequeris et sit.
		    </p>
		    <p>
		Est in vivendum comprehensam conclusionemque, alia cetero iriure no usu, te cibo deterruisset pro. Ludus epicurei quo id, ex cum iudicabit intellegebat. Ex modo deseruisse quo, mel noster menandri sententiae ea, duo et tritani malorum recteque. Nullam suscipit partiendo nec id, indoctum vulputate per ex. Et has enim habemus tibique. Cu latine electram cum, ridens propriae intellegat eu mea.
		    </p>
		    <p>
		Duo at aliquid mnesarchum, nec ne impetus hendrerit. Ius id aeterno debitis atomorum, et sed feugait voluptua, brute tibique no vix. Eos modo esse ex, ei omittam imperdiet pro. Vel assum albucius incorrupte no. Vim viris prompta repudiare ne, vel ut viderer scripserit, dicant appetere argumentum mel ea. Eripuit feugait tincidunt pri ne, cu facilisi molestiae usu.
		    </p>
		    <p>
		Qui utamur tacimates quaestio ad, quod graece omnium ius ut. Pri ut vero debitis interpretaris, qui cu mentitum adipiscing disputationi. Voluptatum mediocritatem quo ut. Fabulas dolorem ei has, quem molestie persequeris et sit.
		    </p>
		    <p>
		Est in vivendum comprehensam conclusionemque, alia cetero iriure no usu, te cibo deterruisset pro. Ludus epicurei quo id, ex cum iudicabit intellegebat. Ex modo deseruisse quo, mel noster menandri sententiae ea, duo et tritani malorum recteque. Nullam suscipit partiendo nec id, indoctum vulputate per ex. Et has enim habemus tibique. Cu latine electram cum, ridens propriae intellegat eu mea.
		    </p>
		    <p>
		Duo at aliquid mnesarchum, nec ne impetus hendrerit. Ius id aeterno debitis atomorum, et sed feugait voluptua, brute tibique no vix. Eos modo esse ex, ei omittam imperdiet pro. Vel assum albucius incorrupte no. Vim viris prompta repudiare ne, vel ut viderer scripserit, dicant appetere argumentum mel ea. Eripuit feugait tincidunt pri ne, cu facilisi molestiae usu.
		    </p>
		  </md-content>
	</div>
	