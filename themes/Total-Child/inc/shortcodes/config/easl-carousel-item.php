<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL Carousel Item', 'total' ),
	'base' => 'easl_carousel_item',
	'is_container' => false,
	'show_settings_on_create' => true,
	'as_child' => array(
		'only' => 'easl_carousel',
	),
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'EASL Carousel Item', 'total' ),
	'icon' => 'vcex-icon ticon ticon-picture-o',
	'php_class_name' => 'EASL_VC_Carousel_Item',
	'params' => array(
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'js_composer' ),
			'param_name' => 'image',
			'value' => '',
			'description' => __( 'Select image from media library.', 'js_composer' ),
			'admin_label' => true,
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Text', 'js_composer' ),
			'param_name' => 'content',
			'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'title', 'total' ),
			'param_name' => 'title',
			'description' => __( 'Enter carousel title.', 'total' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Link', 'total' ),
			'param_name' => 'link',
			'description' => __( 'Enter carousel link.', 'total' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Link Target', 'js_composer' ),
			'param_name' => 'link_target',
			'value' => vc_target_param_list(),
		),
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
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
