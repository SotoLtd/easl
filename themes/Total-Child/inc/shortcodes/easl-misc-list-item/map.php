<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Miscellaneous List Item', 'total-child' ),
	'base'                    => 'easl_misc_list_item',
	'is_container'            => false,
	'show_settings_on_create' => false,
	'as_child'                => array(
		'only' => 'easl_misc_list',
	),
	'category'                => array(
		__( 'EASL', 'total-child' ),
		__( 'National Associations', 'total-child' )
	),
	'description'             => __( 'EASL miscellaneous list item', 'total-child' ),
	//'icon' => 'vcex-icon ticon ticon-th',
	'php_class_name'          => 'EASL_VC_Misc_List_Item',
	'params'                  => array(
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
			'type'        => 'dropdown',
			'heading'     => __( 'Type', 'total-child' ),
			'param_name'  => 'type',
			'value'       => array(
				__( 'Downloadable links', 'total-child' ) => 'downloadable',
				__( 'Image - Excerpt', 'total-child' )    => 'image_content',
				__( 'Title - Excerpt', 'total-child' )    => 'title_excerpt',
			),
			'description' => __( 'Select list item type.', 'total-child' ),
			'group'       => __( 'Data', 'total-child' ),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Image', 'total-child' ),
			'param_name'  => 'image',
			'value'       => '',
			'description' => __( 'Select image from media library.', 'total-child' ),
			'admin_label' => false,
			'group'       => __( 'Data', 'total-child' ),
			'dependency'  => array(
				'element' => 'type',
				'value'   => array( 'image_content' ),
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Title', 'total-child' ),
			'param_name'  => 'title',
			'description' => __( 'Enter title.', 'total-child' ),
			'group'       => __( 'Data', 'total-child' ),
			'admin_label' => true,
		),
		array(
			'type'        => 'textarea',
			'heading'     => __( 'Excerpt', 'total-child' ),
			'param_name'  => 'excerpt',
			'description' => __( 'Enter excerpt.', 'total-child' ),
			'group'       => __( 'Data', 'total-child' ),
			'dependency'  => array(
				'element' => 'type',
				'value'   => array( 'image_content', 'title_excerpt' ),
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Link Text', 'total-child' ),
			'param_name'  => 'link_text',
			'description' => __( 'Enter link text.', 'total-child' ),
			'group'       => __( 'Data', 'total-child' ),
			'dependency'  => array(
				'element' => 'type',
				'value'   => array( 'image_content', 'title_excerpt' ),
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Link Url', 'total-child' ),
			'param_name'  => 'link_url',
			'description' => __( 'Enter link url.', 'total-child' ),
			'group'       => __( 'Data', 'total-child' ),
		),
		array(
			'type'        => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'     => __( 'Open Link in New Tab', 'total-child' ),
			'param_name'  => 'link_target',
			'description' => __( 'Enter link url.', 'total-child' ),
			'group'       => __( 'Data', 'total-child' ),
			'dependency'  => array(
				'element' => 'type',
				'value'   => array( 'image_content', 'title_excerpt' ),
			),
		)
	),
);
