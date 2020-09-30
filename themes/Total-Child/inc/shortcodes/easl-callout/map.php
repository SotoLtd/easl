<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Callout', 'total-child' ),
	'base'                    => 'easl_callout',
	'icon'                    => 'vcex-icon-box vcex-icon ticon ticon-bullhorn',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL Small Events', 'total-child' ),
	'description'             => __( 'Add a callout section with button.', 'total-child' ),
	'php_class_name'          => 'EASL_VC_Heading',
	'params'                  => array(
		array(
			'type'			 => 'textarea_html',
			'heading'		 => __( 'Text', 'total-child' ),
			'param_name'	 => 'content',
			'description'	 => __( 'Enter callout text.', 'total-child' ),
			'admin_label' => true,
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Title', 'total-child' ),
			'param_name' => 'button_title',
			'admin_label' => true,
			'group'       => __( 'Button', 'total-child' ),
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'URL', 'total-child' ),
			'param_name' => 'button_url',
			'admin_label' => true,
			'group'       => __( 'Button', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'    => __( 'Open in new Tab', 'total-child' ),
			'param_name' => 'button_nt',
			'group'      => __( 'Button', 'total-child' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Color', 'total-child' ),
			'param_name' => 'button_color',
			'admin_label' => false,
			'value'      => array(
				'Default - Light Blue'    => '',
				'Blue'       => 'blue',
				'Light Blue' => 'light-blue',
				'Red'        => 'red',
				'Teal'       => 'teal',
				'Orange'     => 'orange',
				'Grey'       => 'grey',
				'Yellow'     => 'yellow',
			),
			'group'      => __( 'Button', 'total-child' ),
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
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'admin_label' => false,
			'group'      => __( 'Design Options', 'js_composer' ),
		),
	),
);
