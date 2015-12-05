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
	
	
	function upload_image_admin_ajax() {
		$file = $_FILES['file'];
		$movefile = wp_handle_upload( $file, array( 'test_form' => false ) );
		$data = $_POST;
		if( isset($data['resize']) ) {
			$width = isset($data['resize']['width']) ? (int)$data['resize']['width'] : 1400;
			$height = isset($data['resize']['height']) ? (int)$data['resize']['height'] : 1400;
			$crop = isset($data['resize']['crop']) ? (bool)$data['resize']['crop'] : false;
			
			$ext = pathinfo($movefile['file'], PATHINFO_EXTENSION);
			$filename = str_replace('.'.$ext,'',basename($movefile['file']));
			
			
			//$loc = image_resize( $movefile['file'], $width, $height, $crop, $suffix = null, $dest_path = null, $jpeg_quality = 80 );
			$img = new SimpleImage($movefile['file']);
			$dir = wp_upload_dir();
			$save_loc = $dir['path'].'/'.$filename.'-'.$width.'x'.$height.'.'.$ext;
			if( $crop ) {
				$img->adaptive_resize($width, $height)->save($save_loc);
			} else {
				$img->best_fit($width, $height)->save($save_loc);
			}
			
			
			$movefile['file'] = $save_loc;
			$movefile['url'] = '/'.str_replace(get_home_path(), '', $save_loc);
			$movefile['size'] = getimagesize($save_loc);
			
		}
		else {
			$movefile['url'] = str_replace(site_url(), '', $movefile['url']);
			$movefile['size'] = getimagesize($movefile['file']);
		}
		
		echo json_encode($movefile);
		die;
	}
}