<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
function easl_vc_get_shortcodes() {
	$shortcodes = array(
		'easl_table_of_content',
	);

	return $shortcodes;
}

function easl_vc_register_shortcodes() {
	$inc_dir       = get_stylesheet_directory() . '/inc';
	$shortcode_dir = $inc_dir . '/shortcodes-v2';
	require_once $shortcode_dir . '/class-easl-shortcode.php';

	$shortcodes = easl_vc_get_shortcodes();
	foreach ( $shortcodes as $shortcode ) {
		$file_name  = str_replace( 'easl_', '', $shortcode );
		$file_name  = str_replace( '_', '-', $file_name );
		$file_name  = strtolower( $file_name );
		$class_file = $shortcode_dir . "/{$file_name}/{$file_name}.php";
		$map_file   = $shortcode_dir . "/{$file_name}/map.php";
		if ( file_exists( $class_file ) ) {
			require_once $class_file;
		}
		if ( file_exists( $map_file ) ) {
			vc_lean_map( $shortcode, null, $map_file );
		}
	}
}

add_action( 'vc_after_mapping', 'easl_vc_register_shortcodes', 10 );