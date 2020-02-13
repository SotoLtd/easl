<?php
if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
function sc_easl_toggle($atts, $content=null) {
	ob_start();
	$atts = shortcode_atts(array(
		'title' => '',
	), $atts);
	include( get_stylesheet_directory() . '/vc_templates/easl_toggle.php' );
	return ob_get_clean();
}
add_shortcode('easl_toggle', 'sc_easl_toggle');