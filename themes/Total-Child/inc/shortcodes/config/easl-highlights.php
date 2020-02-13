<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
return array(
	'name' => __( 'EASL Highlights', 'total' ),
	'base' => 'easl_highlights',
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'EASL Highlights', 'total' ),
	'icon' => 'vcex-icon ticon ticon-lightbulb-o',
	'php_class_name' => 'EASL_Vc_Highlights',
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'All Event URL', 'total-child' ),
			'param_name' => 'events_link',
			'description' => __( 'Enter all events page url.', 'total-child' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'All Publications URL', 'total-child' ),
			'param_name' => 'publications_link',
			'description' => __( 'Enter all publications page url.', 'total-child' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'All Slide Decks URL', 'total-child' ),
			'param_name' => 'slide_decks_link',
			'description' => __( 'Enter all slide decks page url.', 'total-child' ),
		),
		vc_map_add_css_animation(),
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ),
				'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' ),
		),
	),
);