<?
/**
 * ajax class
 *
 * Methods postfixed with _admin_ajax OR _both_ajax will be accessible when a user is signed in
 * Methods postfixed with _nopriv_ajax  OR _both_ajax will be accessible to a non signed in user
 * @package WordPress:: Custom Theme
 * @author Tim Williams
 */
class ajax
{
	// --> stores angular POST input
	private $json_input;
	
	function __construct() {
		
		$this->json_input = json_decode(file_get_contents("php://input"), true);
		
		foreach( get_class_methods( $this ) as $method ) {
			if( stristr($method, '_admin_ajax') ) {
				$ajax_path = str_replace(array('_admin_ajax','_'),array('','-'), $method);
				add_action('wp_ajax_'.$ajax_path, array($this,$method));
			}
		}
		foreach( get_class_methods( $this ) as $method ) {
			if( stristr($method, '_both_ajax') || stristr($method, '_nopriv_ajax') ) {
				$ajax_path = str_replace(array('_nopriv_ajax','_both_ajax','_'),array('','','-'), $method);
				add_action('wp_ajax_nopriv_'.$ajax_path, array($this,$method));
				add_action('wp_ajax_'.$ajax_path, array($this,$method));
			} 
		}
	}
	
}