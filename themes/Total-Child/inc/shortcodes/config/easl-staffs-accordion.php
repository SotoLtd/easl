<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Staff Accordion', 'total-child' ),
	'base'                    => 'easl_staff_accordion',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL', 'total' ),
	'description'             => __( 'Add staff in accordion style.', 'total-child' ),
	'icon'                    => 'vcex-icon ticon ticon-users',
	'php_class_name'          => 'EASL_VC_Staffs_Accordion',
	'params'                  => array(
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Widget Title', 'total-child' ),
			'param_name'  => 'widget_title',
			'admin_label' => true,
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Element Id', 'total-child' ),
			'param_name' => 'el_id',
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'total-child' ),
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
			'param_name'  => 'el_class',
		),
		vcex_vc_map_add_css_animation(),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Query Type', 'total-child' ),
			'param_name' => 'query_type',
			'group'      => __( 'Query', 'total-child' ),
			'value'      => array(
				__( 'Include', 'total' )  => 'include',
				__( 'Category', 'total' ) => 'category',
			),
		),
		array(
			'type'               => 'autocomplete',
			'heading'            => __( 'Include Staff', 'total-child' ),
			'param_name'         => 'include_stuffs',
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
			'dependency'         => array(
				'element' => 'query_type',
				'value'   => array( 'include' ),
			),
			'group'              => __( 'Query', 'total-child' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Number of staff', 'total-child' ),
			'param_name'  => 'staffs_number',
			'value'       => '',
			'description' => __( 'Enter the limit of staff to show. Leave empty to show all.', 'total-child' ),
			'dependency'  => array(
				'element' => 'query_type',
				'value'   => array( 'category' ),
			),
			'group'       => __( 'Query', 'total-child' ),
		),
		array(
			'type'               => 'autocomplete',
			'heading'            => __( 'Include Categories', 'total-child' ),
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
			'dependency'         => array(
				'element' => 'query_type',
				'value'   => array( 'category' ),
			),
			'group'              => __( 'Query', 'total-child' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Multiple Category Relation', 'total-child' ),
			'param_name' => 'cat_relation',
			'group'      => __( 'Query', 'total-child' ),
			'value'      => array(
				__( 'IN', 'total' )         => 'IN',
				__( 'NOT IN', 'total' )     => 'NOT IN',
				__( 'AND', 'total' )        => 'AND',
				__( 'EXISTS', 'total' )     => 'EXISTS',
				__( 'NOT EXISTS', 'total' ) => 'NOT EXISTS',
			),
			'dependency' => array(
				'element' => 'query_type',
				'value'   => array( 'category' ),
			),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Order', 'total-child' ),
			'param_name' => 'order',
			'group'      => __( 'Query', 'total-child' ),
			'value'      => array(
				__( 'Default', 'total' ) => '',
				__( 'DESC', 'total' )    => 'DESC',
				__( 'ASC', 'total' )     => 'ASC',
			),
			'dependency' => array(
				'element' => 'query_type',
				'value'   => array( 'category' ),
			),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Order By', 'total-child' ),
			'param_name' => 'orderby',
			'value'      => array(
				__( 'Default', 'total-child' )    => '',
				__( 'Date', 'total-child' )       => 'date',
				__( 'Title', 'total-child' )      => 'title',
				__( 'ID', 'total-child' )         => 'ID',
				__( 'Menu Order', 'total-child' ) => 'menu_order',
				//__( 'Last Name', 'total' ) => 'last_name',
			),
			'dependency' => array(
				'element' => 'query_type',
				'value'   => array( 'category' ),
			),
			'group'      => __( 'Query', 'total' ),
		),
		// Staff item Layout
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'true',
			'heading'    => __( 'Show country in accordion title', 'total-child' ),
			'param_name' => 'show_country_in_title',
			'group'      => __( 'View', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'true',
			'heading'    => __( 'Show Thumb', 'total-child' ),
			'param_name' => 'show_thumb',
			'group'      => __( 'View', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'true',
			'heading'    => __( 'Show Title', 'total-child' ),
			'param_name' => 'show_title',
			'group'      => __( 'View', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'true',
			'heading'    => __( 'Show Bio', 'total-child' ),
			'param_name' => 'show_bio',
			'group'      => __( 'View', 'total-child' ),
		),
		// Content CSS
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'Content CSS', 'total-child' ),
			'param_name' => 'content_css',
			'group'      => __( 'Content CSS', 'total-child' ),
		),
	),
);
