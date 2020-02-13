<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'                    => __( 'Youtube Video Player', 'total-child' ),
	'base'                    => 'easl_yt_player',
	'icon'                    => 'vcex-icon-box vcex-icon ticon ticon-youtube-play easl-yt-player-icon',
	'is_container'            => false,
	'show_settings_on_create' => true,
	'category'                => __( 'EASL', 'total-child' ),
	'description'             => __( 'Display youtube video player', 'total-child' ),
	'php_class_name'          => 'EASL_VC_Youtube_Player',
	'params'                  => array(
		vc_map_add_css_animation(),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'total-child' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'total-child' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'total-child' ),
			'param_name'  => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Video ID', 'total-child' ),
			'admin_label' => true,
			'param_name'  => 'video_id',
			'value'       => '',
			'description' => __( 'Enter the youtube video ID.', 'total-child' ),
			'group'       => __( 'Video Info', 'total' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Video Start', 'total-child' ),
			'param_name'  => 'video_start',
			'value'       => '',
			'description' => __( 'Enter the number of seconds from where the video starts.', 'total-child' ),
			'group'       => __( 'Video Info', 'total' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Video End', 'total-child' ),
			'param_name'  => 'video_end',
			'value'       => '',
			'description' => __( 'Enter the number of seconds at where the video ends.', 'total-child' ),
			'group'       => __( 'Video Info', 'total' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'    => __( 'Autoplay', 'total-child' ),
			'param_name' => 'autoplay',
			'group'      => __( 'Video Info', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'    => __( 'Mute Audio', 'total-child' ),
			'param_name' => 'mute',
			'group'      => __( 'Video Info', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'true',
			'heading'    => __( 'Controls', 'total-child' ),
			'param_name' => 'controls',
			'group'      => __( 'Video Info', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'    => __( 'Hide Youtube Branding', 'total-child' ),
			'param_name' => 'modestbranding',
			'group'      => __( 'Video Info', 'total-child' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Video Title', 'total-child' ),
			'param_name'  => 'widget_title',
			'admin_label' => true,
			'group'      => __( 'View', 'total-child' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Title Position', 'total-child' ),
			'param_name' => 'title_pos',
			'value'      => array(
				__( 'Top', 'total-child' ) => 'top',
				__( 'Bottom', 'total-child' )   => 'bottom',
			),
			'group'      => __( 'View', 'total-child' ),
		),
		array(
			'type'       => 'dropdown',
			'heading'    => __( 'Video Cover Photo Source', 'total-child' ),
			'param_name' => 'cover_image_type',
			'value'      => array(
				__( 'Media Library', 'total-child' ) => 'media_lib',
				__( 'Youtube', 'total-child' )   => 'yt',
			),
			'group'      => __( 'View', 'total-child' ),
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Image', 'total-child' ),
			'param_name' => 'cover_image',
			'value' => '',
			'description' => __( 'Select image from media library.', 'total-child' ),
			'admin_label' => false,
			'dependency'	 => array(
				'element'	 => 'cover_image_type',
				'value'		 => array( 'media_lib' ),
			),
			'group'      => __( 'View', 'total-child' ),
		),
		// Content CSS
		array(
			'type'		 => 'css_editor',
			'heading'	 => __( 'CSS box', 'total-child' ),
			'param_name' => 'css',
			'group'		 => __( 'total-child', 'total-child' ),
		),
		array(
			'type'       => 'vcex_ofswitch',
			'std'        => 'false',
			'heading'    => __( 'Show Video in a lightbox', 'total-child' ),
			'param_name' => 'lightbox',
			'group'      => __( 'View', 'total-child' ),
		),
	),
);
