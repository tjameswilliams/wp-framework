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
	
  // --> Javascript is now processed with gulp. During development run 'gulp watch' in the theme directory
  
	wp_register_script('minified_javascript', $theme_uri.'/compiled/all.min.js', [], filemtime(TEMPLATEPATH.'/compiled/all.min.js'));
	wp_enqueue_script('minified_javascript');
  
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
  $deps = json_decode(file_get_contents(TEMPLATEPATH.'/dependencies.json'),true);
  if( !empty($deps['admin']) && !empty($deps['admin']['js'])) {
    foreach($deps['admin']['js'] as $script) {
    	wp_register_script($script,  get_template_directory_uri().'/'.$script, array('jquery'), filemtime(TEMPLATEPATH.'/'.$script));
    	wp_enqueue_script($script);
    }
  }
}

add_action( 'admin_enqueue_scripts', 'fw\admin_scripts' );

function deregister_scripts(){
  wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'fw\deregister_scripts' );

/**
 * fw\styles function
 *
 * Registers theme styles.
 * @return void
 * @author Tim Williams
 */
function styles()
{
	$all_css = '/compiled/all.min.css';
	wp_register_style('style', get_template_directory_uri().$all_css, array(), filemtime(TEMPLATEPATH.$all_css), 'all');
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
	$menu_str = wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'container'		  => false, 
		'echo'			  => false,
		'fallback_cb'	  => 'jsonNavwalker::fallback',
		'items_wrap'	  => '[%3$s]',
		'depth'			  => 2,
		'walker'		  => new \jsonNavwalker()
		)
	);
  if( $menu_ob = json_decode(str_replace('}{', '},{',$menu_str),true)) {
    return buildTree($menu_ob);
  } else {
    return $menu_str;
  }
  // Fucking wordpress wants you to use a class to output your menu markup, I say NO THANK YOU FUCKERS.
}

function buildTree(array $elements, $parentId = 0) {
  $branch = array();

  foreach ($elements as $element) {
    if ($element['menu_item_parent'] == $parentId) {
      $children = buildTree($elements, $element['ID']);
      if ($children) {
        $element['children'] = $children;
      }
      $branch[] = $element;
    }
  }

  return $branch;
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


show_admin_bar( false );