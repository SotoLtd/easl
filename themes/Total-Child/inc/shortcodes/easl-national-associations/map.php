<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'           => __( 'EASL National Associations', 'total-child' ),
	'base'           => 'easl_national_associations',
	'category'       => array(
		__( 'EASL', 'total-child' ),
		__( 'National Associations', 'total-child' )
	),
	'description'    => __( 'EASL National Associations', 'total-child' ),
	'icon'           => 'vcex-icon ticon ticon-users',
	'php_class_name' => 'EASL_VC_National_Associations',
	'params'         => array(
		vc_map_add_css_animation(),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'total-child' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'total-child' ),
				'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'total-child' ),
			'param_name'  => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
		),
		// Contribute Box
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'true',
			'heading'    => __( 'Enabble', 'total-child' ),
			'param_name' => 'contribute_enable',
			'group'       => __( 'Contribute', 'total-child' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Box Title', 'total-child' ),
			'param_name'  => 'contribute_title',
			'value'       => '',
			'description' => __( 'Enter contribute box title', 'total-child' ),
			'group'       => __( 'Contribute', 'total-child' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Box Subitle', 'total-child' ),
			'param_name'  => 'contribute_subtitle',
			'value'       => '',
			'description' => __( 'Enter contribute box sub title', 'total-child' ),
			'group'       => __( 'Contribute', 'total-child' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Button Title', 'total-child' ),
			'param_name'  => 'contribute_btitle',
			'value'       => '',
			'description' => __( 'Enter contribute box button title', 'total-child' ),
			'group'       => __( 'Contribute', 'total-child' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Button Color', 'total-child' ),
			'param_name'  => 'contribute_bcolor',
			'value'       => array(
				__( 'Primary(Blue)', 'total-child' )         => 'primary',
				__( 'Secondary(Light Blue)', 'total-child' ) => 'secondary',
				__( 'Red', 'total-child' )                   => 'red',
				__( 'Teal', 'total-child' )                  => 'teal',
				__( 'Orange', 'total-child' )                => 'orange',
				__( 'Gray', 'total-child' )                  => 'gray',
				__( 'Yellow', 'total-child' )                => 'yellow',
			),
			'description' => __( 'Select button background color.', 'total-child' ),
			'group'       => __( 'Contribute', 'total-child' ),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Overlay Form', 'total-child' ),
			'param_name'  => 'contribute_form',
			'value'       => EASL_VC_National_Associations::get_forms_dropdown(),
			'description' => __( 'Select overlay form.', 'total-child' ),
			'group'       => __( 'Contribute', 'total-child' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Overlay Form Title', 'total-child' ),
			'param_name'  => 'contribute_form_title',
			'value'       => '',
			'description' => __( 'Enter overlay form title', 'total-child' ),
			'group'       => __( 'Contribute', 'total-child' ),
		),
		// Css editor box
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group'      => __( 'Design Options', 'total-child' ),
		),
	),
);