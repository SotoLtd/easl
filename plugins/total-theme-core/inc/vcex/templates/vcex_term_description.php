<?php
/**
 * vcex_term_description shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

$shortcode_tag = 'vcex_term_description';

if ( ! vcex_maybe_display_shortcode( $shortcode_tag, $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( $shortcode_tag, $atts, $this );

if ( vcex_vc_is_inline() ) {
	$term_description = esc_html( 'Term description placeholder for the live builder.', 'total' );
} else {
	$term_description = term_description();
}

if ( empty( $term_description ) ) {
	return;
}

$shortcode_class = array(
	'vcex-module',
	'vcex-term-description',
	'wpex-last-mb-0',
);

$extra_classes = vcex_get_shortcode_extra_classes( $atts, $shortcode_tag );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, $shortcode_tag, $atts );

if ( $atts['font_family'] ) {
	vcex_enqueue_font( $atts['font_family'] );
}

$shortcode_style = vcex_inline_style( array(
	'color'              => $atts['color'],
	'font_family'        => $atts['font_family'],
	'font_size'          => $atts['font_size'],
	'font_weight'        => $atts['font_weight'],
	'line_height'        => $atts['line_height'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), true );

$shortcode_data = '';
if ( $rfont_size = vcex_get_responsive_font_size_data( $atts['font_size'] ) ) {
	$shortcode_data = "data-wpex-rcss='" . htmlspecialchars( wp_json_encode( array( 'font-size' => $rfont_size ) ) ) . "'";
}

// Begin output
$output = '<div class="' . esc_attr( trim( $shortcode_class ) ) . '"' . $shortcode_style . $shortcode_data . '>';
	$output .= do_shortcode( wp_kses_post( $term_description ) );
$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;