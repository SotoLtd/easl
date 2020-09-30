<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Table of Content', 'total-child' ),
	'base'                    => 'easl_table_of_content',
	'icon'                    => 'vcex-icon ticon ticon-list-alt',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL', 'total-child' ),
	'description'             => __( 'Add a callout section with button.', 'total-child' ),
	'php_class_name'          => 'EASL_VC_Table_Of_Content',
	'params'                  => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Widget Heading', 'total-child' ),
			'param_name'  => 'heading',
			'admin_label' => true,
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Display Type', 'total-child' ),
			'param_name'  => 'display_type',
			'admin_label' => false,
			'value'       => array(
				'Inline' => '',
				'List'   => 'list',
			),
		),
		array(
			'type'       => 'param_group',
			'heading'    => __( 'Items', 'total-child' ),
			'param_name' => 'items',
			'group'      => __( 'Items', 'total-child' ),
			'value'      => urlencode( json_encode( array(
				array(
					'i_title'  => '',
					'i_target' => ''
				),
			) ) ),
			'params'     => array(
				array(
					'type'        => 'textfield',
					'value'       => '',
					'param_name'  => 'i_title',
					'heading'     => __( 'Title', 'total-child' ),
					'admin_label' => true,
				),
				array(
					'type'        => 'textfield',
					'value'       => '',
					'param_name'  => 'i_target',
					'heading'     => __( 'Target ID', 'total-child' ),
					'admin_label' => false,
				),
			),
		),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'js_composer' ),
			'param_name'  => 'el_id',
			'admin_label' => false,
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'js_composer' ),
			'param_name'  => 'el_class',
			'admin_label' => false,
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type'        => 'css_editor',
			'heading'     => __( 'CSS box', 'js_composer' ),
			'param_name'  => 'css',
			'admin_label' => false,
			'group'       => __( 'Design Options', 'js_composer' ),
		),
	),
);
