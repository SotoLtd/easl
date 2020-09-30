<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


return array(
	'name' => __( 'CTA Title Button', 'total-child' ),
	'base' => 'easl_cta_title_button',
	'category' => __( 'EASL', 'total-child' ),
	'description' => __( 'EASL Button', 'total-child' ),
	'icon' => 'vcex-icon ticon ticon-external-link-square',
	'php_class_name' => 'EASL_VC_CTA_Title_Button',
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Button Title', 'total-child' ),
			'param_name' => 'button_title',
			'description' => __( 'Enter button title.', 'total-child' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Button Link', 'total-child' ),
			'param_name' => 'button_link',
			'description' => __( 'Enter button link.', 'total-child' ),
		),
		array(
			'type'			 => 'textarea_html',
			'heading'		 => __( 'Text', 'total-child' ),
			'param_name'	 => 'content',
			'description'	 => __( 'Enter ingtroduction text.', 'total-child' ),
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
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'total-child' ),
		),
	),
);
