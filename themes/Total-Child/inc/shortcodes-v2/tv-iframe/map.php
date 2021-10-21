<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL TV iframe', 'total-child' ),
	'base'                    => 'easl_tv_iframe',
	'icon'                    => 'vcex-icon ticon ticon-television',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL', 'total-child' ),
	'description'             => __( 'Embed EASL TV iframe.', 'total-child' ),
	'php_class_name'          => 'EASL_VC_TV_Iframe',
	'params'                  => array(
        array(
            'type'       => 'textfield',
            'heading'    => __( 'Vieo Link (iframe url)', 'total-child' ),
            'param_name' => 'iframe_link',
            'admin_label' => true,
        ),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'js_composer' ),
			'param_name'  => 'el_id',
			'admin_label' => false,
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'js_composer' ),
			'param_name'  => 'el_class',
			'admin_label' => false,
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type'        => 'css_editor',
			'heading'     => __( 'CSS box', 'js_composer' ),
			'param_name'  => 'css',
			'admin_label' => false,
			'group'       => __( 'Design option', 'js_composer' ),
		),
	),
);
