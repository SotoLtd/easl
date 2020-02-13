<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL Awardees', 'total' ),
	'base' => 'easl_awardees',
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'EASL Awardees', 'total' ),
	'icon' => 'vcex-icon ticon ticon-users',
	'php_class_name' => 'EASL_VC_Awardees',
	'params' => array(
		vc_map_add_css_animation(),
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'js_composer' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ),
				'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type'		 => 'dropdown',
			'heading'	 => __( 'Staffs per row', 'total' ),
			'param_name' => 'staff_col_width',
			'std'		 => '3',
			'value'		 => array(
				__( '1 Item', 'total' )	 => '1',
				__( '2 Item', 'total' )	 => '2',
				__( '3 Item', 'total' )	 => '3',
				__( '4 Item', 'total' )	 => '4',
			),
		),
		// Query
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of Awardees to show', 'total' ),
			'param_name' => 'limit',
			'value' => '9',
			'description' => __( 'leave to empty to display all.', 'total' ),
			'group' => __( 'Query', 'total' ),
		),
		array(
			'type' => 'autocomplete',
			'heading' => __( 'Include Tags', 'total' ),
			'param_name' => 'include_tags',
			'param_holder_class' => 'vc_not-for-custom',
			'admin_label' => true,
			'settings' => array(
				'multiple' => true,
				'min_length' => 1,
				'groups' => false,
				'unique_values' => true,
				'display_inline' => true,
				'delay' => 0,
				'auto_focus' => true,
			),
			'group' => __( 'Query', 'total' ),
		),
		array(
			'type' => 'autocomplete',
			'heading' => __( 'Exclude Tags', 'total' ),
			'param_name' => 'exclude_tags',
			'param_holder_class' => 'vc_not-for-custom',
			'admin_label' => true,
			'settings' => array(
				'multiple' => true,
				'min_length' => 1,
				'groups' => false,
				'unique_values' => true,
				'display_inline' => true,
				'delay' => 0,
				'auto_focus' => true,
			),
			'group' => __( 'Query', 'total' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order', 'total' ),
			'param_name' => 'order',
			'group' => __( 'Query', 'total' ),
			'value' => array(
				__( 'Default', 'total' ) => '',
				__( 'DESC', 'total' ) => 'DESC',
				__( 'ASC', 'total' ) => 'ASC',
			),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order By', 'total' ),
			'param_name' => 'orderby',
			'value' => vcex_orderby_array(),
			'group' => __( 'Query', 'total' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Orderby: Meta Key', 'total' ),
			'param_name' => 'orderby_meta_key',
			'group' => __( 'Query', 'total' ),
			'dependency' => array(
				'element' => 'orderby',
				'value' => array( 'meta_value_num', 'meta_value' ),
			),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' ),
		),
	),
);