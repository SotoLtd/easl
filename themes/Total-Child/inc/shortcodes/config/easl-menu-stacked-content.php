<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$custom_menus = array();
if ( 'vc_edit_form' === vc_post_param( 'action' ) && vc_verify_admin_nonce() ) {
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	if ( is_array( $menus ) && ! empty( $menus ) ) {
		foreach ( $menus as $single_menu ) {
			if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->term_id ) ) {
				$custom_menus[ $single_menu->name ] = $single_menu->term_id;
			}
		}
	}
}
return array(
	'name' => __( 'EASL Menu Stacked Content', 'total' ),
	'base' => 'easl_menu_stacked_content',
	'is_container' => true,
	'show_settings_on_create' => false,
	'category' => __( 'EASL', 'total' ),
	'description' => __( 'EASL Menu Stacked Content', 'total' ),
	'php_class_name' => 'EASL_VC_Menu_Stacked_content',
	'js_view' => 'VcColumnView',
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'js_composer' ),
			'param_name' => 'title',
			'description' => __( 'What text use as a widget title. Leave blank to use default widget title.', 'js_composer' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Menu', 'js_composer' ),
			'param_name' => 'nav_menu',
			'value' => $custom_menus,
			'description' => empty( $custom_menus ) ? __( 'Custom menus not found. Please visit <b>Appearance > Menus</b> page to create new menu.', 'js_composer' ) : __( 'Select menu to display.', 'js_composer' ),
			'admin_label' => true,
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Layout', 'js_composer' ),
			'param_name' => 'layout',
			'value' => array(
				__( 'Horizontal', 'js_composer' ) => 'horizontal',
				__( 'Vertical', 'js_composer' ) => 'vertical',
			),
			'description' => empty( $custom_menus ) ? __( 'Select layout.', 'js_composer' ) : __( 'Select menu to display.', 'js_composer' ),
			'admin_label' => false,
		),
		array(
			'type' => 'vcex_ofswitch',
			'heading' => __( 'Show Filter on Top', 'js_composer' ),
			'std' => 'false',
			'param_name' => 'enable_right_menu_filter',
			'admin_label' => false,
			'dependency' => array(
				'element'	 => 'layout',
				'value'		 => array( 'horizontal' ),
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'All filter link', 'js_composer' ),
			'param_name' => 'filter_all_link',
			'dependency' => array(
				'element'	 => 'enable_right_menu_filter',
				'value'		 => array( 'true' ),
			),
		),
		array(
			'type' => 'vcex_ofswitch',
			'heading' => __( 'Show All Tabs on Mobile', 'js_composer' ),
			'std' => 'false',
			'param_name' => 'show_all_tabs_on_mobile',
			'admin_label' => false,
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
