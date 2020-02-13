<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL Storylne 3D Slider Item', 'total' ),
	'base' => 'easl_s3d_slider_item',
	'is_container' => false,
	'show_settings_on_create' => false,
	'as_child' => array(
		'only' => 'easl_s3d_slider',
	),
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'EASL Storylne 3D Slider Item', 'total' ),
	'icon' => 'vcex-icon ticon ticon-picture-o',
	'php_class_name' => 'EASL_VC_S3D_Slider_Item',
	'params' => array(
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'js_composer' ),
			'param_name' => 'image',
			'value' => '',
			'description' => __( 'Select image from media library.', 'js_composer' ),
			'admin_label' => false,
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'title', 'total' ),
			'param_name' => 'title',
			'description' => __( 'Enter carousel title.', 'total' ),
			'admin_label' => true,
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
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
	),
);
