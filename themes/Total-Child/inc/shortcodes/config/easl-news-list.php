<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$newsletter_years = EASL_Newsletter_Config::get_years();
$nl_years = array(__('All Year', 'total-child') => '');
foreach($newsletter_years as $y){
	$nl_years[(string)$y] = (string)$y;
}

return array(
	'name' => __( 'EASL News List', 'total-child' ),
	'base' => 'easl_news_list',
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'EASL News', 'total-child' ),
	'icon' => 'vcex-icon ticon ticon-list-alt',
	'php_class_name' => 'EASL_VC_News_List',
	'params' => array(
		vc_map_add_css_animation(),
		array(
			'type' => 'el_id',
			'heading' => __( 'Element ID', 'total-child' ),
			'param_name' => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'total-child' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'total-child' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
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
			'group'			 => __( 'Query', 'total-child' ),
		),
		array(
			'type'		 => 'vcex_ofswitch',
			'std'		 => 'true',
			'heading'	 => __( 'Show Filter', 'total-child' ),
			'param_name' => 'show_filter',
			'group'				 => __( 'View', 'total-child' ),
		),
		array(
			'type'			 => 'textfield',
			'heading'		 => __( 'Excerpt Length', 'total-child' ),
			'param_name'	 => 'excerpt_length',
			'value'			 => '32',
			'description'	 => __( 'Enter excerpt words limit.', 'total-child' ),
			'group'				 => __( 'View', 'total-child' ),
		),
		// Newsletter
		array(
			'type' => 'vcex_ofswitch',
			'std' => 'true',
			'heading' => __( 'Display Newsletters?', 'total-child' ),
			'param_name' => 'display_newsletters',
			'group' => __( 'Newsletters', 'total' ),
			'description' => __( 'Enable to display newsletter.', 'total-child' ),
		),
		array(
			'type'			 => 'textfield',
			'heading'		 => __( 'Column Title', 'total-chid' ),
			'param_name'	 => 'nl_title',
			'value'			 => '',
			'description'	 => __( 'Enter the title of newsletters column.', 'total-chid' ),
			'group'			 => __( 'Newsletters', 'total-child' ),
			'dependency' => array( 'element' => 'display_newsletters', 'value' => array( 'true' ) ),
		),
		array(
			'type'			 => 'textfield',
			'heading'		 => __( 'Number of newsletter', 'total-chid' ),
			'param_name'	 => 'nl_limit',
			'value'			 => '',
			'description'	 => __( 'Enter the limit of newsletters to show. Leave empty to show all.', 'total-chid' ),
			'group'			 => __( 'Newsletters', 'total-child' ),
			'dependency' => array( 'element' => 'display_newsletters', 'value' => array( 'true' ) ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Year', 'total-child' ),
			'param_name' => 'nl_year',
			'group' => __( 'Newsletters', 'total-child' ),
			'value' => $nl_years,
			'dependency' => array( 'element' => 'display_newsletters', 'value' => array( 'true' ) ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'total-child' ),
		),
	),
);
