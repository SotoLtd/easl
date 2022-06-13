<?php
/**
 * vcex_page_title shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2.9
 */

defined( 'ABSPATH' ) || exit;

$shortcode_tag = 'vcex_page_title';

if ( ! vcex_maybe_display_shortcode( $shortcode_tag, $atts ) ) {
	return;
}

$atts = vcex_shortcode_atts( $shortcode_tag, $atts, $this );

$title = vcex_get_the_title();

if ( empty( $title ) ) {
	return;
}

$shortcode_class = array(
	'vcex-module',
	'vcex-page-title',
);

if ( $atts['width'] ) {

	$shortcode_class[] = 'wpex-max-w-100';

	switch ( $atts['float'] ) {
		case 'left':
			$shortcode_class[] = 'wpex-float-left';
			break;
		case 'right':
			$shortcode_class[] = 'wpex-float-right';
			break;
		case 'center':
		default:
			$shortcode_class[] = 'wpex-mx-auto';
			break;
	}

}


$extra_classes = vcex_get_shortcode_extra_classes( $atts, $shortcode_tag );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, $shortcode_tag, $atts );

if ( $atts['font_family'] ) {
	vcex_enqueue_font( $atts['font_family'] );
}

$data = '';
if ( $rfont_size = vcex_get_responsive_font_size_data( $atts['font_size'] ) ) {
	$data = "data-wpex-rcss='" . htmlspecialchars( wp_json_encode( array( 'font-size' => $rfont_size ) ) ) . "'";
}

$shortcode_style = vcex_inline_style( array(
	'width'              => $atts['width'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), true );

// Begin output.
$output = '<div class="' . esc_attr( trim( $shortcode_class ) ) . '"' . $shortcode_style . '>';

	$tag_escaped = tag_escape( $atts['html_tag'] );

	$heading_style = vcex_inline_style( array(
		'color'       => $atts['color'],
		'font_family' => $atts['font_family'],
		'font_size'   => $atts['font_size'],
		'line_height' => $atts['line_height'],
		'font_weight' => $atts['font_weight'],
	), true );

	$output .= '<' . $tag_escaped . ' class="wpex-heading vcex-page-title__heading wpex-text-3xl"' . $heading_style . $data . '>';

		if ( $atts['before_text'] ) {
			$output .= '<span class="vcex-page-title__before">' . do_shortcode( esc_html( $atts['before_text'] ) ) . '</span> ';
		}

		$output .= '<span class="vcex-page-title__text">' .  do_shortcode( wp_kses_post( $title ) ) . '</span>';

		if ( $atts['after_text'] ) {
			$output .= ' <span class="vcex-page-title__after">' . do_shortcode( esc_html( $atts['after_text'] ) ) . '</span>';
		}

	$output .= '</' . $tag_escaped . '>';

$output .= '</div>';

if ( $atts['width'] && 'center' !== $atts['float'] ) {
	$output .= '<div class="vcex-clear--page_title wpex-clear"></div>';
}

// @codingStandardsIgnoreLine
echo $output;