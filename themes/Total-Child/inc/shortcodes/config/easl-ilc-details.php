<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$ilcs_dd = array_merge( array(array('label' => __('Latest ILC', 'total-child'), 'value' => '')), EASL_ILC_Config::get_ilcs());

return array(
	'name' => __( 'EASL ILC Details', 'total-child' ),
	'base' => 'easl_ilc_details',
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'Display details of an ILC.', 'total-child' ),
	'icon' => 'vcex-skill-bar vcex-icon ticon ticon-server',
	'php_class_name' => 'EASL_VC_ILC_Details',
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
		// Query
		array(
			'type' => 'dropdown',
			'heading' => __( 'ILC', 'total-child' ),
			'param_name' => 'ilc_id',
			'group' => __( 'Query', 'total-child' ),
			'value' => $ilcs_dd,
		),
		// View
		array(
			'type' => 'vcex_ofswitch',
			'std' => 'true',
			'heading' => __( 'Display Selector?', 'total-child' ),
			'param_name' => 'display_selector',
			'group' => __( 'View', 'total-child' ),
			'description' => __( 'Enable to display ILC selector.', 'total-child' ),
		),
		array(
			'type'			 => 'textfield',
			'heading'		 => __( 'Selector Title', 'total-chid' ),
			'param_name'	 => 'selector_title',
			'value'			 => 'Show me contet from',
			'description'	 => __( 'Enter the title of the ilc selector dropdown.', 'total-chid' ),
			'group'			 => __( 'View', 'total-child' ),
			'dependency' => array( 'element' => 'display_selector', 'value' => array( 'true' ) ),
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'total-child' ),
		),
	),
);
