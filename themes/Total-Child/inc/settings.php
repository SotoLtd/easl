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
    $easl_clock_settings = acf_add_options_page( array(
        'page_title' => 'EASL Clock',
        'menu_title' => 'Clock',
        'menu_slug'  => 'easl-clock-settings',
        'capability' => 'manage_options',
        'parent_slug' => 'easl-settings',
        'redirect'   => false,
    ) );
}