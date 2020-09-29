<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( function_exists( 'acf_add_options_page' ) ) {
	$easl_settings_top = acf_add_options_page( array(
		'page_title' => 'EASL Settings',
		'menu_slug'  => 'easl-settings',
		'capability' => 'manage_options',
		'redirect'   => false,
	) );
}