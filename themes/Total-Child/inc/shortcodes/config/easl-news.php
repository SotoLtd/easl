<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL News', 'total' ),
	'base' => 'easl_news',
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'EASL News', 'total' ),
	'icon' => 'vcex-icon ticon ticon-list-alt',
	'php_class_name' => 'EASL_VC_News',
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'js_composer' ),
		),
		array(
			'type' => 'checkbox',
			'param_name' => 'view_all_link',
			'heading' => __( 'Show view all link?', 'total' ),
			'description' => __( 'Enable view all links beside widget title.', 'total' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'View All links Text', 'js_composer' ),
			'param_name' => 'view_all_text',
			'description' => __( 'Enter text used as view all events link.', 'js_composer' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'View All links URL', 'js_composer' ),
			'param_name' => 'view_all_url',
			'description' => __( 'Enter URL used as view all events link.', 'js_composer' ),
		),
		array(
			'type'				 => 'autocomplete',
			'heading'			 => __( 'Include Categories', 'total-child' ),
			'param_name'		 => 'include_categories',
			'param_holder_class' => 'vc_not-for-custom',
			'admin_label'		 => true,
			'settings'			 => array(
				'multiple'		 => true,
				'min_length'	 => 1,
				'groups'		 => false,
				'unique_values'	 => true,
				'display_inline' => true,
				'delay'			 => 0,
				'auto_focus'	 => true,
			),
			'group'				 => __( 'Query', 'total-child' ),
		),
		array(
			'type'				 => 'autocomplete',
			'heading'			 => __( 'Exclude Categories', 'total-child' ),
			'param_name'		 => 'exclude_categories',
			'param_holder_class' => 'vc_not-for-custom',
			'admin_label'		 => true,
			'settings'			 => array(
				'multiple'		 => true,
				'min_length'	 => 1,
				'groups'		 => false,
				'unique_values'	 => true,
				'display_inline' => true,
				'delay'			 => 0,
				'auto_focus'	 => true,
			),
			'group'				 => __( 'Query', 'total-child' ),
		),
		array(
			'type'			 => 'textfield',
			'heading'		 => __( 'Number of news', 'total-child' ),
			'param_name'	 => 'limit',
			'value'			 => '6',
			'description'	 => __( 'Enter the limit of news to show. Leave empty to show all.', 'total-child' ),
			'group'				 => __( 'Query', 'total-child' ),
		),
		array(
			'type'			 => 'textfield',
			'heading'		 => __( 'Excerpt Length', 'total-child' ),
			'param_name'	 => 'excerpt_length',
			'value'			 => '28',
			'description'	 => __( 'Enter excerpt words limit.', 'total-child' ),
			'group'				 => __( 'View', 'total-child' ),
		),
		vc_map_add_css_animation(),
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
