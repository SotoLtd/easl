<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function easl_textarea_shortcode_param( $settings, $value ) {
	$settings['rows'] = isset( $settings['rows'] ) ? absint( $settings['rows'] ) : 3;
	$settings['rows'] = min( 2, $settings['rows'] );

	return '<textarea name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textarea ' . $settings['param_name'] . ' ' . $settings['type'] . '" rows="' . $settings['rows'] . '">' . $value . '</textarea>';
}

vc_add_shortcode_param(
	'easl_textarea',
	'easl_textarea_shortcode_param'
);

function easl_textarea_raw_shortcode_param( $settings, $value ) {
	$settings['rows'] = isset( $settings['rows'] ) ? absint( $settings['rows'] ) : 3;
	$settings['rows'] = min( 2, $settings['rows'] );

	return sprintf( '<textarea name="%s" class="wpb_vc_param_value wpb-textarea_raw_html %s %s" rows="%s">%s</textarea>', $settings['param_name'], $settings['param_name'], $settings['type'], $settings['rows'], htmlentities( rawurldecode( base64_decode( $value ) ), ENT_COMPAT, 'UTF-8' ) );
}

vc_add_shortcode_param(
	'easl_textarea_raw',
	'easl_textarea_raw_shortcode_param'
);