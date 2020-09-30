<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Heading', 'total-child' ),
	'base'                    => 'easl_heading',
	'icon'                    => 'vcex-icon-box vcex-icon ticon ticon-header',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL Small Events', 'total-child' ),
	'description'             => __( 'Add a heading.', 'total-child' ),
	'php_class_name'          => 'EASL_VC_Heading',
	'params'                  => array(
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Text', 'total-child' ),
			'param_name' => 'text',
			'admin_label' => true,
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Heading Type', 'total-child' ),
			'param_name' => 'type',
			'value'      => array(
				'H1' => 'h1',
				'H2' => 'h2',
				'H3' => 'h3',
				'H4' => 'h4',
				'H5' => 'h5',
			),
			'std'        => 'h2'
		),
//		array(
//			'type'       => 'dropdown',
//			'heading'    => __( 'Heading Color', 'total-child' ),
//			'param_name' => 'color',
//			'value'      => array(
//				'Primary - Light Blue'  => 'primary',
//				'Secondary - Deep blue' => 'secondary',
//				'Gray'                  => 'gray',
//			),
//			'std'        => 'secondary',
//		),
		vc_map_add_css_animation(),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'js_composer' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'js_composer' ),
			'param_name'  => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group'      => __( 'Design Options', 'js_composer' ),
		),
	),
);
