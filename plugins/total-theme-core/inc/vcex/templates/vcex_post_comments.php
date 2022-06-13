<?php
/**
 * vcex_post_comments shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

$shortcode_tag = 'vcex_post_comments';

if ( ! vcex_maybe_display_shortcode( $shortcode_tag, $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( $shortcode_tag, $atts, $this );

$wrap_class = array(
	'vcex-comments',
);

if ( ! empty( $atts['el_class'] ) ) {
	$wrap_class[] = vcex_get_extra_class( $atts['el_class'] );
}

if ( $atts['bottom_margin'] ) {
	$wrap_class[] = vcex_sanitize_margin_class( $atts['bottom_margin'], 'wpex-mb-' );
}

if ( ! empty( $atts['visibility'] ) ) {
	$wrap_class[] = esc_attr( $atts['visibility'] );
}

if ( empty( $atts['show_heading'] ) || 'false' == $atts['show_heading'] ) {
	$wrap_class[] = 'vcex-comments-hide-heading';
}

$wrap_class = vcex_parse_shortcode_classes( implode( ' ', $wrap_class ), $shortcode_tag, $atts );

$output = '<div class="' . esc_attr( $wrap_class ) . '">';

	ob_start();
		comments_template();
	$output .= ob_get_clean();

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;