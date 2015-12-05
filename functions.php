<?
namespace fw;
/*
 *	Author: Tim Williams
 *	URL: http://newpdev.com
 *	Wordpress Custom Site Framework
 */


spl_autoload_register('fw\autoload_classes');

/**
 * fw\autoload_classes function
 *
 * Allows classes to be included without explicitly writing an include for each class file each time a class is required.
 * @return void
 * @author Tim Williams
 */
function autoload_classes( $class_name ) 
{
	if( file_exists( TEMPLATEPATH . '/classes/' . $class_name . '.class.php' ) )
	{
		include TEMPLATEPATH . '/classes/' . $class_name . '.class.php';
	}
}

// --> parses the .env file for environment conditionals
new \environment();

/**
 * fw\scripts function
 * 
 * Handles enqueueing all theme required scripts, also handles minifying the JavaScript in live environments
 * @return void
 * @author Tim Williams
 */
function scripts()
{
	$theme_uri = get_template_directory_uri();
	
	$scripts = array(
		'jquery' => array(
			'version' => '1',
			'dependencies' => array(),
			'location' => '/node_modules/jquery/dist/jquery.min.js'
		),
		'tmce' => array(
			'version' => '1',
			'dependencies' => array(),
			'location' => '/node_modules/tinymce/tinymce.jquery.min.js'
		),
		'angular' => array(
			'version' => '1',
			'dependencies' => array(),
			'location' => '/node_modules/angular/angular.min.js'
		),
		'angular-ui-bootstrap' => array(
			'version' => '1',
			'dependencies' => array('angular'),
			'location' => '/node_modules/angular-bootstrap/ui-bootstrap.min.js'
		),
		'angular-ui-tinymce' => array(
			'version' => '1',
			'dependencies' => array('angular'),
			'location' => '/node_modules/angular-ui-tinymce/src/tinymce.js'
		),
		'angular-ui-bootstrap-tpls' => array(
			'version' => '1',
			'dependencies' => array('angular','angular-ui-bootstrap'),
			'location' => '/node_modules/angular-bootstrap/ui-bootstrap-tpls.min.js'
		),
		'angular-file-upload' => array(
			'version' => '1',
			'dependencies' => array('angular'),
			'location' => '/node_modules/ng-file-upload/dist/ng-file-upload-all.min.js'
		),
		'angular-ui-sortable' => array(
			'version' => '1',
			'dependencies' => array('angular'),
			'location' => '/node_modules/angular-ui-sortable/src/sortable.js'
		),
		'angular-touch' => array(
			'version' => '1',
			'dependencies' => array('angular'),
			'location' => '/node_modules/angular-touch/angular-touch.min.js'
		),
		'angular-route' => array(
			'version' => '1',
			'dependencies' => array('angular'),
			'location' => '/node_modules/angular-route/angular-route.min.js'
		),
		'fancybox' => array(
			'version' => '1',
			'dependencies' => array('angular'),
			'location' => '/node_modules/fancybox/dist/js/jquery.fancybox.pack.js'
		),
		'angular_app' => array(
			'version' => '1.1',
			'dependencies' => array('angular'),
			'location' => '/js/app.angular.js'
		)
	);
	
	// --> if we are not in production, register scripts as usual
	if( isset($_ENV['ENVIRONMENT']) && $_ENV['ENVIRONMENT'] != 'production' ) {
		foreach( $scripts as $script_name => $atts )
		{
			wp_register_script($script_name, $theme_uri.$atts['location'], $atts['dependencies'], $atts['version']);
			wp_enqueue_script($script_name);
		}
	}
	// --> else, we check if a minified script exits (create if not) and enqueue it.
	else {
		$min_js_loc = __DIR__.'/cache/scripts.min.js';
		if( !file_exists($min_js_loc) || isset($_GET['dumpcache']) ) {
			$minifier = new \minifier();
			$script_min = '';
			foreach( $scripts as $name => $script ) {
				if( !isset($script['no_concat']) || $script['no_concat'] == false ) {
					$script_parse = file_get_contents(__DIR__.$script['location']);
					if( stristr($script['location'],'.min.') !== false ) {
						$script_min .=  "\n\n".$script_parse;
					}
					else
					{
						$script_parse = $minifier->minify($script_parse,array('flaggedComments' => false));
						if( $script_parse !== false ) {
							$script_min .= "\n\n".$script_parse;
						}
					}
				}
			}
			file_put_contents($min_js_loc,$script_min);
		}
		$cache_time = filectime($min_js_loc);
		wp_register_script('minified_javascript', $theme_uri.'/cache/scripts.min.js?cb='.$cache_time, array('jquery'), '1');
		wp_enqueue_script('minified_javascript');
		
		foreach( $scripts as $name => $script ) {
			if( isset($script['no_concat']) && $script['no_concat'] ) {
				wp_register_script($name, $theme_uri.$script['location'], $script['dependencies'], '1');
				wp_enqueue_script($name);
			}
		}
	}
}

add_action('init', 'fw\scripts');

/**
 * fw\admin_classes function
 *
 * Instantiates classes which handle hooks for custom admin functionality. If a class is required for the theme initiation
 * it should be registered here. Please note, be smart about how you register classes, if they are only needed to print
 * the admin pages register them under the is_admin() clause.
 * @return void
 * @author Tim Williams
 */
function admin_classes() {
	if( is_admin() )
	{
		$admin_classes = array('ajax','pageMeta');
		
		foreach( $admin_classes as $admin_class )
		{
			new $admin_class();
		}
	}
	else {
		
		$fe_classes = array('customShortcodes','ajax');
		
		foreach( $fe_classes as $class ) 
		{
			new $class();
		}
		
	}
}

add_action('init', 'fw\admin_classes');

function admin_scripts()
{
	wp_register_script('admin_js',  get_template_directory_uri().'/js/admin.js', array('jquery'), '1');
	wp_enqueue_script('admin_js');
}

add_action( 'admin_enqueue_scripts', 'fw\admin_scripts' );

/**
 * fw\styles function
 *
 * Registers theme styles.
 * @return void
 * @author Tim Williams
 */
function styles()
{
	wp_register_style('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css', array(), '1.0', 'all');
	wp_enqueue_style('bootstrap'); // Enqueue it!
	
	wp_register_style('fancybox', get_template_directory_uri() . '/node_modules/fancybox/dist/css/jquery.fancybox.css', array(), '1.0', 'all');
	wp_enqueue_style('fancybox'); // Enqueue it!
	
	wp_register_style('style', get_template_directory_uri() . '/style.css', array(), '1.2', 'all');
	wp_enqueue_style('style'); // Enqueue it!
}

add_action('wp_enqueue_scripts', 'fw\styles');

add_action( 'after_setup_theme', function() {
	register_nav_menu( 'header-menu', 'Primary Menu' );
});

/**
 * fw\nav function
 * 
 * prints a bootstrap compatible nav bar.
 * @return string
 * @author Tim Williams
 */
function nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'			  => '', 
		'container'		  => false, 
		'container_class' => 'collapse navbar-collapse', 
		'container_id'	  => '',
		'menu_class'	  => 'nav navbar-nav navbar-right', 
		'menu_id'		  => '',
		'echo'			  => true,
		'fallback_cb'	  => 'wp_bootstrap_navwalker::fallback',
		'before'		  => '',
		'after'			  => '',
		'link_before'	  => '',
		'link_after'	  => '',
		'items_wrap'	  => '<ul class="nav navbar-nav navbar-right">%3$s</ul>',
		'depth'			  => 2,
		'walker'		  => new \wp_bootstrap_navwalker()
		)
	);
	
}

/**
 * fw\load_view function
 *
 * @param  String  $template
 * @param  Array  $data
 * 
 * It is best practice to separate view code from processing logic. Use this helper function to easily load
 * and parse php based views instead of writing inline HTML within admin functions.
 * @return String, parsed HTML template
 * @author Tim Williams
 */
function load_view( $template, $data = array() )
{
	ob_start();
	extract( $data );
	include __DIR__ . '/views/' . $template . '.tpl';
	return ob_get_clean();
}
