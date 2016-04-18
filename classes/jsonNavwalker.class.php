<?
/**
 * jsonNavwalker class
 *
 * I seriously disagree with WordPress' propensity to build HTML within classes using string concatenation
 * This may make it easier for developers not familiar with working with recursive data, however, if one
 * was to want to modify that HTML, doing it this way would be extreemely difficult. This class simply json encodes
 * the menu objects so they can be consumed by a view or API request or whatever.
 * @package wp-framework
 * @author Tim Williams
 */

class jsonNavwalker extends Walker_Nav_Menu {

  
  // Displays start of a level. E.g '<ul>'
  // @see Walker::start_lvl()
  function start_lvl(&$output, $depth=0, $args=array()) {
    $output .= "";
  }
 
  // Displays end of a level. E.g '</ul>'
  // @see Walker::end_lvl()
  function end_lvl(&$output, $depth=0, $args=array()) {
    $output .= "";
  }
 
  // Displays start of an element. E.g '<li> Item Name'
  // @see Walker::start_el()
  function start_el(&$output, $item, $depth=0, $args=array()) {
    //$output = rtrim($output,',');
   // $output .= ",";
  }
 
  // Displays end of an element. E.g '</li>'
  // @see Walker::end_el()
  function end_el(&$output, $item, $depth=0, $args=array()) {
    //$output .= "";
  }

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
    if ( ! $element )
                return;
    
    
    $output .= json_encode($element);
    $id_field = $this->db_fields['id'];
    
    if ( is_object( $args[0] ) )
        $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
    
    parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
  }

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$fb_output = null;

			if ( $container ) {
				$fb_output = '<' . $container;

				if ( $container_id )
					$fb_output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$fb_output .= ' class="' . $container_class . '"';

				$fb_output .= '>';
			}

			$fb_output .= '<ul';

			if ( $menu_id )
				$fb_output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$fb_output .= ' class="' . $menu_class . '"';

			$fb_output .= '>';
			$fb_output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$fb_output .= '</ul>';

			if ( $container )
				$fb_output .= '</' . $container . '>';

			echo $fb_output;
		}
	}
}