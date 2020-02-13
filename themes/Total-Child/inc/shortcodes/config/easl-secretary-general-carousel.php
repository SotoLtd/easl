<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL Secretary General Carousel', 'total-child' ),
	'base' => 'easl_secretary_general_carousel',
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'Display secretary generals as a carousel.', 'total-child' ),
	'icon' => 'vcex-icon ticon ticon-files-o',
	'php_class_name' => 'EASL_VC_Secretary_Generals_Carousel',
	'params' => array(
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
			'heading'		 => __( 'Number of Secretary Generals', 'total-child' ),
			'param_name'	 => 'limit',
			'value'			 => '',
			'description'	 => __( 'Enter the limit of secretary generals to show. Leave empty to show all.', 'total-child' ),
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
				__( 'Year', 'total-child' ) => 'year',
				__( 'Name', 'total-child' ) => 'name',
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
