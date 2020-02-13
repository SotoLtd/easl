<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Staff', 'total' ),
	'base'                    => 'easl_staffs',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL', 'total' ),
	'description'             => __( 'EASL Staff List', 'total' ),
	'icon'                    => 'vcex-icon ticon ticon-users',
	'php_class_name'          => 'EASL_VC_Staffs',
	'params'                  => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Widget Title', 'total' ),
			'param_name'  => 'widget_title',
			'admin_label' => true,
		),
		array(
			'type'        => 'textarea_html',
			'heading'     => __( 'Introduction', 'js_composer' ),
			'param_name'  => 'content',
			'description' => __( 'Enter introduction text.', 'js_composer' ),
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Element Id', 'total' ),
			'param_name' => 'el_id',
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'total' ),
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total' ),
			'param_name'  => 'el_class',
		),
		vcex_vc_map_add_css_animation(),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Number of staff', 'total' ),
			'param_name'  => 'staffs_number',
			'value'       => '',
			'description' => __( 'Enter the limit of staff to show. Leave empty to show all.', 'total' ),
			'group'       => __( 'Query', 'total' ),
		),
		array(
			'type'               => 'autocomplete',
			'heading'            => __( 'Include Categories', 'total' ),
			'param_name'         => 'include_categories',
			'param_holder_class' => 'vc_not-for-custom',
			'admin_label'        => true,
			'settings'           => array(
				'multiple'       => true,
				'min_length'     => 1,
				'groups'         => false,
				'unique_values'  => true,
				'display_inline' => true,
				'delay'          => 0,
				'auto_focus'     => true,
			),
			'group'              => __( 'Query', 'total' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Multiple Category Relation', 'total' ),
			'param_name' => 'cat_relation',
			'group'      => __( 'Query', 'total' ),
			'value'      => array(
				__( 'IN', 'total' )         => 'IN',
				__( 'NOT IN', 'total' )     => 'NOT IN',
				__( 'AND', 'total' )        => 'AND',
				__( 'EXISTS', 'total' )     => 'EXISTS',
				__( 'NOT EXISTS', 'total' ) => 'NOT EXISTS',
			),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Order', 'total' ),
			'param_name' => 'order',
			'group'      => __( 'Query', 'total' ),
			'value'      => array(
				__( 'Default', 'total' ) => '',
				__( 'DESC', 'total' )    => 'DESC',
				__( 'ASC', 'total' )     => 'ASC',
			),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Order By', 'total' ),
			'param_name' => 'orderby',
			'value'      => array(
				__( 'Default', 'total-child' )    => '',
				__( 'Title', 'total-child' )      => 'title',
				__( 'First Name', 'total-child' ) => 'first_name',
				__( 'Last Name', 'total-child' )  => 'last_name',
				__( 'ID', 'total-child' )         => 'ID',
				__( 'Menu Order', 'total-child' ) => 'menu_order',
			),
			'group'      => __( 'Query', 'total' ),
		),
		// Staff item Layout
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Staff per row', 'total' ),
			'param_name' => 'staff_col_width',
			'std'        => '2',
			'value'      => array(
				__( '1 Item', 'total' ) => '1',
				__( '2 Item', 'total' ) => '2',
				__( '3 Item', 'total' ) => '3',
			),
			'group'      => __( 'Item Layout', 'total' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Item Content Layout', 'total' ),
			'param_name' => 'item_content_layout',
			'std'        => 'single_col',
			'value'      => array(
				__( 'Image Top - Single Column', 'total' ) => 'single_col',
				__( 'Image Left - Two column', 'total' )   => 'two_col',
			),
			'group'      => __( 'Item Layout', 'total' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'    => __( 'Enable info template', 'total' ),
			'param_name' => 'enable_info_template',
			'group'      => __( 'Item Layout', 'total' ),
		),
		array(
			'type'        => 'textarea',
			'std'         => '{{contact_info}}',
			'heading'     => __( 'Info template', 'total' ),
			'param_name'  => 'info_template',
			'group'       => __( 'Item Layout', 'total' ),
			'dependency'  => array(
				'element' => 'enable_info_template',
				'value'   => array( 'true' ),
			),
			'description' => sprintf( __( 'Available staff fields: %s.', 'total' ), '{{' . implode( "}},{{", EASL_VC_Staffs::get_staff_fields() ) . '}}' ),
		),
		// Content CSS
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'Content CSS', 'total' ),
			'param_name' => 'content_css',
			'group'      => __( 'Content CSS', 'total' ),
		),
	),
);
