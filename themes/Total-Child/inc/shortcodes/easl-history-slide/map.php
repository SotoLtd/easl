<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'           => __( 'EASL History Slide', 'total-child' ),
	'base'           => 'easl_history_slide',
	'category'       => __( 'EASL', 'total-child' ),
	'description'    => __( 'Display history slide.', 'total-child' ),
	'icon'           => 'vcex-icon ticon ticon-files-o',
	'php_class_name' => 'EASL_VC_History_Slide',
	'params'         => array(
		vc_map_add_css_animation(),
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
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Number of histories', 'total-child' ),
			'param_name'  => 'limit',
			'value'       => '',
			'description' => __( 'Enter the limit of histories to show. Leave empty to show all.', 'total-child' ),
			'group'       => __( 'Query', 'total-child' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Order', 'total-child' ),
			'param_name' => 'order',
			'value'      => array(
				__( 'Default', 'total-child' ) => '',
				__( 'DESC', 'total-child' )    => 'DESC',
				__( 'ASC', 'total-child' )     => 'ASC',
			),
			'group'      => __( 'Query', 'total-child' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Title type', 'total-child' ),
			'param_name' => 'title_type',
			'group'      => __( 'Query', 'total-child' ),
			'value'      => array(
				__( 'Image', 'total-child' ) => 'image',
				__( 'Text', 'total-child' )  => 'text',
			),
			'group'      => __( 'View', 'total-child' ),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Title Image', 'total-child' ),
			'param_name'  => 'title_image',
			'value'       => '',
			'description' => __( 'Select image from media library.', 'total-child' ),
			'admin_label' => false,
			'group'       => __( 'View', 'total-child' ),
			'dependency'  => array(
				'element' => 'title_type',
				'value'   => array( 'image' ),
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title Text', 'total-child' ),
			'param_name'  => 'title_text',
			'value'       => '',
			'description' => __( 'Enter title text.', 'total-child' ),
			'admin_label' => false,
			'group'       => __( 'View', 'total-child' ),
			'dependency'  => array(
				'element' => 'title_type',
				'value'   => array( 'text' ),
			),
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group'      => __( 'Design Options', 'total-child' ),
		),
	),
);
