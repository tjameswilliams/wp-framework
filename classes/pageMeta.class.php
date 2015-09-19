<?
/**
 * pageMeta class
 *
 * @package WordPress::framework
 * @author Tim Williams
 */
class pageMeta
{
	
	private $post_id;
	
	/**
	 * __construct function
	 *
	 * hooks into add_meta_boxes and save_post and routes to the proper method
	 * @return void
	 * @author Tim Williams
	 */
	function __construct()
	{
		add_action( 'add_meta_boxes', array( $this, '_init' ) );
		add_action( 'save_post', array($this, '_init_save') );
	}
	
	/**
	 * _init function
	 *
	 * Conditionally applies meta fields to a page editor if a page template has them.
	 * @return void
	 * @author Tim Williams
	 */
	function _init( $post_type )
	{
		if( $post_type == 'page' ) {
			$post_id = $_POST['post'] ? $_POST['post'] : $_GET['post'];
			$this->post_id = $post_id;
			$page_template_method = $this->_get_page_template_method($post_id);
			
			if ( method_exists($this, $page_template_method) ) {
				add_meta_box(
					$page_template_method
					,str_replace('_', ' ', $page_template_method)
					,array( $this, $page_template_method )
					,$post_type
					,'advanced'
					,'high'
				);
			}
			
			if ( method_exists($this, 'all') ) {
				add_meta_box(
					'all_pages'
					,'Configuration'
					,array( $this, 'all' )
					,$post_type
					,'advanced'
					,'high'
				);
			}
		}
	}
	
	/**
	 * _init_save function
	 *
	 * @return void
	 * @author Tim Williams
	 */
	function _init_save($post_id)
	{
		$this->post_id = $post_id;
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return $post_id;
		}
				// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			
			if ( ! current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
			
			$page_template_save_method = $this->_get_page_template_method($post_id).'_save';
			
			if( method_exists($this,$page_template_save_method) ) {
				$this->$page_template_save_method($post_id);
			}
			
			if( method_exists($this,'all_save') ) {
				$this->all_save($post_id);
			}
		}
	}
	
	/**
	 * _get_page_template_method function
	 *
	 * @return void
	 * @author Tim Williams
	 */
	function _get_page_template_method( $post_id )
	{
		return str_replace(array('templates/','.php','-'), array('','','_'), get_post_meta( $post_id, '_wp_page_template', true ));
	}
	
	/**
	 * _get_field_values function
	 *
	 * @param  Array, $fields (fills the key 'value')
	 * 
	 * Supply an array describing fields ex:
	 * array(
	 *   array(
	 *     'name'=> 'field_1',
	 *     'label' => 'Field One',
	 *     'type'=> 'select',
	 *     'options'=> array(
	 *       'one' => 'Option 1',
	 *       'two' => 'Option 2'
	 *     )
	 *   )
	 * )
	 * This function will decorate all fields with their meta values is previously saved.
	 * @return array
	 * @author Tim Williams
	 */
	function _get_field_values( $fields )
	{
		if( !empty($fields) ) {
			foreach( $fields as &$field ) {
				$field['value'] = get_post_meta ( $this->post_id, $field['name'], true );
			}
		}
		return $fields;
	}
	
	/**
	 * _save_input function
	 *
	 * @param Array $names  to save to meta fields
	 * 
	 * Supply an array of names ex. array('field_1','field_2') and each field will be pulled from $_POST and saved
	 * to the current page's meta
	 * @return void
	 * @author Tim Williams
	 */
	function _save_input( $names )
	{
		if( !empty($names) ) {
			foreach( $names as $name ) {
				update_post_meta($this->post_id, $name, $_POST[$name]);
			}
		}
	}
	
	/**
	 * all function
	 *
	 * Meta applied to all pages
	 * @return void
	 * @author Tim Williams
	 */
	function all()
	{
		
		
	}
	
	/**
	 * all_save function
	 *
	 * Save method for meta applied to all pages
	 * @return void
	 * @author Tim Williams
	 */
	function all_save($post_id)
	{
		
	}
	
	
} // END class 