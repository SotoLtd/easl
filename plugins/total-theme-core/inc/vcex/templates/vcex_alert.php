<?php
/**
 * vcex_alert shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

$shortcode_tag = 'vcex_alert';

if ( ! vcex_maybe_display_shortcode( $shortcode_tag, $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( $shortcode_tag, $atts, $this );

if ( empty( $content ) ) {
	return;
}

$shortcode_class = array(
	'vcex-module',
	'wpex-alert',
);

if ( $atts['type'] ) {
	$shortcode_class[] = 'wpex-alert-' . sanitize_html_class( $atts['type'] );
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, $shortcode_tag );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, $shortcode_tag, $atts );

$shortcode_style = vcex_inline_style( array(
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
) );

$output = '<div class="' . esc_attr( trim( $shortcode_class ) ) . '"' . $shortcode_style . '>';

	if ( $atts['heading'] ) {
		$output .= '<h4>' . do_shortcode( wp_kses_post( $atts['heading'] ) ) . '</h4>';
	}

	$output .= do_shortcode( wp_kses_post( $content ) );

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;