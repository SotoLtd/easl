<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'EASL Events Key Dates', 'total-child' ),
	'base'                    => 'easl_events_key_dates',
	'icon'                    => 'vcex-icon-box vcex-icon ticon ticon-header',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL Events', 'total-child' ),
	'description'             => __( 'Display events key dates', 'total-child' ),
	'php_class_name'          => 'EASL_VC_Events_Key_Dates',
	'params'                  => array(
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Title', 'total-child' ),
			'param_name' => 'title',
			'admin_label' => true,
		),
        array(
            'type'               => 'autocomplete',
            'heading'            => __( 'Select Event', 'total' ),
            'param_name'         => 'event_id',
            'param_holder_class' => 'vc_not-for-custom',
            'admin_label'        => true,
            'description' => __( 'Select the Event. Leave empty to for displayed event.', 'total' ),
            'settings'           => array(
                'multiple'       => false,
                'min_length'     => 1,
                'groups'         => false,
                'unique_values'  => true,
                'display_inline' => true,
                'delay'          => 0,
                'sortable'       => false,
                'auto_focus'     => true,
            ),
        ),
		vc_map_add_css_animation(),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'js_composer' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'js_composer' ),
			'param_name'  => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group'      => __( 'Design Options', 'js_composer' ),
		),
	),
);
