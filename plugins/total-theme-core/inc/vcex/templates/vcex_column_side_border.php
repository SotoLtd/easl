<?php
/**
 * vcex_column_side_border shortcode output
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2
 */

defined( 'ABSPATH' ) || exit;

$shortcode_tag = 'vcex_column_side_border';

if ( ! vcex_maybe_display_shortcode( $shortcode_tag, $atts ) ) {
	return;
}

if ( vcex_vc_is_inline() ) {
	echo '<div class="wpex-alert wpex-text-center">' . __( 'Column Side Border Placeholder', 'total-theme-code' ) . '</div>';
	return;
}

$atts = vcex_shortcode_atts( $shortcode_tag, $atts, $this );

$shortcode_class = 'vcex-column-side-border';

if ( $atts['class'] ) {
    $shortcode_class .= ' ' . vcex_get_extra_class( $atts['class'] );
}

if ( ! $atts['position'] ) {
	$atts['position'] = 'right';
}

$shortcode_class .= ' vcex-' . sanitize_html_class( $atts['position'] );

if ( $atts['visibility'] ) {
    $shortcode_class .= ' ' . sanitize_html_class( $atts['visibility'] );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, $shortcode_tag, $atts );

$style = '';

if ( $atts['height'] ) {
	$style .= 'height:' . wp_strip_all_tags( $atts['height'] ) .';';
}

if ( $atts['width'] ) {
	$style .= 'width:' . absint( $atts['width'] ) .'px;';

	if ( 'right' === $atts['position'] ) {
		$style .= 'right:-' . absint( $atts['width'] ) / 2 . 'px;';
	}

	if ( 'left' === $atts['position'] ) {
		$style .= 'left:-' . absint( $atts['width'] ) / 2 . 'px;';
	}

}

if ( $atts['background_color'] ) {
	$style .= 'background-color:' . vcex_parse_color( $atts['background_color'] ) .';';
}

if ( $style ) {
	$style = ' style="' . esc_attr( $style ) . '"';
}

echo '<div class="' . esc_attr( $shortcode_class ) . '"' . $style . '></div>';