<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL Background Text Box', 'total-child' ),
	'base' => 'easl_bgtext_box',
	'show_settings_on_create' => true,
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'Add a text box with background.', 'total-child' ),
	'icon' => 'vc_element-icon icon-wpb-layer-shape-text',
	'php_class_name' => 'EASL_VC_BgText_box',
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => __( 'Background Color', 'total-child' ),
			'param_name' => 'bgcolor',
			'std'		 => 'primary',
			'value' => array(
				__('Default(Blue)', 'total-child') => '',
				__('Secondary(Light Blue)', 'total-child') => 'secondary',
				__('Red', 'total-child') => 'red',
				__('Teal', 'total-child') => 'teal',
				__('Orange', 'total-child') => 'orange',
				__('Gray', 'total-child') => 'gray',
				__('Light Gray', 'total-child') => 'light-gray',
				__('Yellow', 'total-child') => 'yellow',
			),
			'admin_label' => true,
		),
		array(
			'type' => 'textarea',
			'holder' => 'div',
			'heading' => __( 'Text', 'total-child' ),
			'param_name' => 'content',
			'value' => __( '', 'total-child' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Text Align', 'total-child' ),
			'param_name' => 'align',
			'value' => array(
				__("Left", 'total-child') => 'left',
				__("Center", 'total-child') => 'center',
				__("Right", 'total-child') => 'right',
			),
			'std' => 'left',
		),

		array(
			'type' => 'dropdown',
			'heading' => __( 'Tag', 'total-child' ),
			'param_name' => 'html_tag',
			'value' => array(
				__("DIV", 'total-child') => 'div',
				__("H1", 'total-child') => 'h1',
				__("H2", 'total-child') => 'h2',
				__("H3", 'total-child') => 'h3',
				__("H4", 'total-child') => 'h4',
				__("H5", 'total-child') => 'h5',
				__("H6", 'total-child') => 'h6',
				__("P", 'total-child') => 'p',
			),
			'std' => 'h3',
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
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' ),
		),
	),
);
