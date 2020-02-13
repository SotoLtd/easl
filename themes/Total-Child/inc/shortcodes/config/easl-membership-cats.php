<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL Membership Categories', 'total-child' ),
	'base' => 'easl_membership_cats',
	'category' => __( 'EASL', 'total-child' ),
	'description' => __( 'Display membership categories as a vertical accordion.', 'total-child' ),
	'icon' => 'icon-wpb-ui-tab-content-vertical',
	'php_class_name' => 'EASL_VC_Membership_Categories',
	'params' => array(array(
		'type' => 'textfield',
		'heading' => __( 'Widget title', 'total-child' ),
		'param_name' => 'title',
		'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'total-child' ),
	),
		vc_map_add_css_animation(),
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'total-child' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'total-child' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'total-child' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
		),
		array(
			'type'			 => 'textfield',
			'heading'		 => __( 'Number of Membership Categories', 'total-child' ),
			'param_name'	 => 'limit',
			'value'			 => '',
			'description'	 => __( 'Enter the limit of membership categories to show. Leave empty to show all.', 'total-child' ),
			'group'			 => __( 'Query', 'total-child' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order', 'total-child' ),
			'param_name' => 'order',
			'value' => array(
				__( 'Default', 'total-child' ) => '',
				__( 'DESC', 'total-child' ) => 'DESC',
				__( 'ASC', 'total-child' ) => 'ASC',
			),
			'group' => __( 'Query', 'total-child' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order By', 'total-child' ),
			'param_name' => 'orderby',
			'value' => array(
				__( 'Default', 'total-child' ) => '',
				__( 'Title', 'total-child' ) => 'title',
				__( 'Menu Order', 'total-child' ) => 'menu_order',
			),
			'group' => __( 'Query', 'total-child' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'total-child' ),
		),
	),
);
