<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Logo Grid', 'total-child' ),
	'base'                    => 'easl_logo_grid',
	'icon'                    => 'vcex-icon-box vcex-icon ticon ticon-th',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL Small Events', 'total-child' ),
	'description'             => __( 'Add a grid of logos.', 'total-child' ),
	'php_class_name'          => 'EASL_VC_Logo_Grid',
	'params'                  => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Heading', 'total-child' ),
			'param_name'  => 'heading',
			'admin_label' => true,
		),
		array(
			'type'        => 'attach_images',
			'heading'     => __( 'Logos', 'total-child' ),
			'param_name'  => 'logos',
			'value'       => '',
			'admin_label' => false,
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'true',
			'heading'    => __( 'Logo Border', 'total-child' ),
			'param_name' => 'border',
			'admin_label' => true,
		),
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
