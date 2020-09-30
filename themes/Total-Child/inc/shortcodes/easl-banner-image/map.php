<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Banner Image', 'total-child' ),
	'base'                    => 'easl_banner_image',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL', 'total-child' ),
	'description'             => __( 'Add a banner image with text over it.', 'total-child' ),
	'icon'                    => 'vcex-icon ticon ticon-picture-o',
	'php_class_name'          => 'EASL_VC_Banner_image',
	'params'                  => array(
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Image', 'total-child' ),
			'param_name'  => 'image',
			'value'       => '',
			'admin_label' => false,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Image Alt', 'total-child' ),
			'param_name'  => 'image_alt',
			'value'       => '',
			'admin_label' => false,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Link', 'total-child' ),
			'param_name'  => 'link',
			'value'       => '',
			'admin_label' => false,
		),
		array(
			'type'       => 'vcex_ofswitch',
			'heading'    => __( 'Open in new Tab', 'total-child' ),
			'param_name' => 'new_tab',
			'std'        => 'true',
			'admin_label' => false,
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'total-child' ),
			'param_name'  => 'title',
			'value'       => '',
			'admin_label' => true,
			'group'       => __( 'Overlay Text', 'total-child' ),
			'description' => 'To add a line break add --NL--',
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Subtitle', 'total-child' ),
			'param_name'  => 'subtitle',
			'value'       => '',
			'group'       => __( 'Overlay Text', 'total-child' ),
			'description' => 'To add a line break add --NL--',
		),
		array(
			'type'        => 'textarea',
			'heading'     => __( 'Text', 'js_composer' ),
			'param_name'  => 'content',
			'admin_label' => false,
			'group'       => __( 'Overlay Text', 'total-child' ),
			'description' => 'Allowed html tags: br, a, span, strong, em, sup, sub',
		),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'total-child' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'total-child' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'total-child' ),
			'param_name'  => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
		),
	),
);
