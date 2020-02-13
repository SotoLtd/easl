<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Miscellaneous List', 'total-child' ),
	'base'                    => 'easl_misc_list',
	'is_container'            => true,
	'show_settings_on_create' => true,
	'as_parent'               => array(
		'only' => 'easl_misc_list_item',
	),
	'category'                => array(
		__( 'EASL', 'total-child' ),
		__( 'National Associations', 'total-child' )
	),
	'description'             => __( 'EASL miscellaneous list', 'total-child' ),
	//'icon' => 'vcex-icon ticon ticon-th',
	'php_class_name'          => 'EASL_VC_Misc_List',
	'js_view'                 => 'VcColumnView',
	'params'                  => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'total-child' ),
			'param_name'  => 'title',
			'admin_label' => true,
			'description' => __( 'Enter list title.', 'total-child' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'total-child' ),
			'param_name' => 'icon',
			'settings' => array(
				'emptyIcon' => true,
				'iconsPerPage' => 4000,
			),
		),
		vc_map_add_css_animation(),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'total-child' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'total-child' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'js_composer' ),
			'param_name'  => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group'      => __( 'Design Options', 'total-child' ),
		),
	),
);
