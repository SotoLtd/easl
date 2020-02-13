<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name' => __( 'EASL Events Calendar', 'total' ),
	'base' => 'easl_events_calendar',
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'EASL Events Calendar', 'total' ),
	'icon' => 'vcex-icon ticon ticon-calendar',
	'php_class_name' => 'EASL_VC_Events_Calendar',
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
			'type' => 'dropdown',
			'heading' => __( 'All Topics Events', 'js_composer' ),
			'param_name' => 'all_topic_events',
			'value' => EASL_VC_Events_Calendar::get_topics_dd_for_vc_map(true),
			'description' => __( 'Select the Topics events of which will be displayed always.', 'js_composer' ),
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
		// Related Link
		array(
			'type'			 => 'vcex_ofswitch',
			'std'			 => 'true',
			'heading'		 => __( 'Enable', 'total-child' ),
			'param_name'	 => 'enable_related_links',
			'group'			 => __( 'Related Links', 'total-child' ),
		),
		array(
			'type'		 => 'textfield',
			'heading'	 => __( 'Title', 'total-child' ),
			'param_name' => 'relink_title',
			'value'		 => 'Related Links',
			'group'		 => __( 'Related Links', 'total-child' ),
			'dependency' => array(
				'element'	 => 'enable_related_links',
				'value'		 => array( 'true' ),
			),
		),
		array(
			'type'		 => 'param_group',
			'heading'	 => __( 'Related Links', 'total-child' ),
			'param_name' => 'related_links',
			'group'		 => __( 'Related Links', 'total-child' ),
			'dependency' => array(
				'element'	 => 'enable_related_links',
				'value'		 => array( 'true' ),
			),
			'value'		 => urlencode( json_encode( array(
				array(
					'rlink' => '',
				),
			) ) ),
			'params'	 => array(
				array(
					'type'			 => 'vc_link',
					'value'			 => '',
					'param_name'	 => 'rlink',
					'heading'		 => __( 'Related link data', 'total-child' ),
					'admin_label'	 => true,
				),
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
