<?
/**
 * customShortcodes class
 *
 * @package WordPress::custom theme
 * @author Tim Williams
 */
class customShortcodes {
	
	function __construct() {
		foreach( get_class_methods( $this ) as $method ) {
			if( stristr($method, '_shortcode') ) {
				add_shortcode(str_replace('_shortcode', '', $method), array($this,$method));
			}
		}
	}
	
	/* SHORTCODES */
	function quote_shortcode( $atts, $content = null )
	{
		return '<blockquote> ' . do_shortcode($content) . ' </blockquote>';
	}
	
	function source_shortcode( $atts, $content = null )
	{
		return '<footer><em>' . do_shortcode($content) . '</em></footer>';
	}

	function row_shortcode( $atts, $content = null )
	{
		return '<div class="row"> ' . do_shortcode($content) . ' </div>';
	}
	
	function two_col_shortcode( $atts, $content = null )
	{
		return '<div class="col-sm-6"> ' . do_shortcode($content) . ' </div>';
	}
	
}