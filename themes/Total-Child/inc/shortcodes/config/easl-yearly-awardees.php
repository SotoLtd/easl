<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL Yearly Awardees', 'total-child' ),
	'base' => 'easl_yearly_awardees',
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'List awardees by year', 'total-child' ),
	'icon' => 'vcex-icon ticon ticon-users',
	'php_class_name' => 'EASL_VC_Yearly_Awardee',
	'params' => array(
		vc_map_add_css_animation(),
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'total-child' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'total-child' ),
				'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'total-child' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
		),
		array(
			'type'		 => 'dropdown',
			'heading'	 => __( 'Award Title Type', 'total-child' ),
			'param_name' => 'award_title_type',
			'value'		 => array(
				__( 'Award year', 'total-child' )	 => 'year',
				__( 'Award title', 'total-child' )	 => 'title',
			),
			'group'		 => __( 'View', 'total-child' ),
		),
		array(
			'type'		 => 'dropdown',
			'heading'	 => __( 'Peoples Per Row', 'total-child' ),
			'param_name' => 'people_per_row',
			'std'		 => '3',
			'value'		 => array(
				__( '1 Item', 'total-child' )	 => '1',
				__( '2 Item', 'total-child' )	 => '2',
				__( '3 Item', 'total-child' )	 => '3',
				__( '4 Item', 'total-child' )	 => '4',
			),
			'group'		 => __( 'View', 'total-child' ),
		),
		array(
			'type'		 => 'vcex_ofswitch',
			'std'		 => 'false',
			'heading'	 => __( 'Display Award Thumb', 'total-child' ),
			'param_name' => 'display_thumb',
			'description' => __('If enabled the award thubnail wil be shown at the end of list.', 'total-child'),
			'group'		 => __( 'View', 'total-child' ),
		),
		// Query
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Query Type', 'total-child' ),
			'param_name' => 'query_type',
			'group'      => __( 'Query', 'total-child' ),
			'value'      => array(
				__( 'Normal', 'total-child' ) => 'normal',
				__( 'Include Only', 'total-child' )  => 'include',
			),
		),
		array(
			'type'               => 'autocomplete',
			'heading'            => __( 'Include Awards', 'total-child' ),
			'param_name'         => 'include_awards',
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
			'type' => 'autocomplete',
			'heading' => __( 'Award Type', 'total-child' ),
			'param_name' => 'award_type',
			'param_holder_class' => 'vc_not-for-custom',
			'admin_label' => true,
			'settings' => array(
				'multiple' => false,
				'min_length' => 1,
				'groups' => false,
				'unique_values' => true,
				'display_inline' => true,
				'delay' => 0,
				'auto_focus' => true,
			),
			'dependency'         => array(
				'element' => 'query_type',
				'value'   => array( 'normal' ),
			),
			'group' => __( 'Query', 'total-child' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of Years to show', 'total-child' ),
			'param_name' => 'year_num',
			'value' => '',
			'description' => __( 'leave to empty to display all years.', 'total-child' ),
			'dependency'         => array(
				'element' => 'query_type',
				'value'   => array( 'normal' ),
			),
			'group' => __( 'Query', 'total-child' ),
		),
		array(
			'type'		 => 'vcex_ofswitch',
			'std'		 => 'false',
			'heading'	 => __( 'Past Years Only', 'total-child' ),
			'param_name' => 'past_year_only',
			'dependency'         => array(
				'element' => 'query_type',
				'value'   => array( 'normal' ),
			),
			'group'		 => __( 'Query', 'total-child' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'People Order', 'total-child' ),
			'param_name' => 'people_order',
			'value' => array(
				__( 'Default', 'total-child' ) => '',
				__( 'DESC', 'total-child' ) => 'DESC',
				__( 'ASC', 'total-child' ) => 'ASC',
			),
			'group' => __( 'Query', 'total-child' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'People Order By', 'total-child' ),
			'param_name' => 'people_orderby',
			'value' => array(
				__( 'Default', 'total-child' ) => '',
				__( 'First name', 'total-child' ) => 'first_name',
				__( 'Last name', 'total-child' ) => 'last_name',
				__( 'ID', 'total-child' ) => 'ID',
				__( 'Title', 'total-child' ) => 'title',
				__( 'Menu Order', 'total-child' ) => 'menu_order',
			),
			'group' => __( 'Query', 'total-child' ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'total-child' ),
		),
	),
);