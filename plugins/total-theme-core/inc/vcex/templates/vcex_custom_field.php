<?php
/**
 * vcex_custom_field shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

$shortcode_tag = 'vcex_custom_field';

if ( ! vcex_maybe_display_shortcode( $shortcode_tag, $atts ) ) {
	return;
}

// Get shortcode attributes.
$atts = vcex_shortcode_atts( $shortcode_tag, $atts, $this );

// Name required.
if ( empty( $atts['name'] ) ) {
	return;
}

$output = '';
$cf_value = '';
$custom_field_name = $atts['name'];

if ( shortcode_exists( 'acf' ) ) {
	$cf_value = do_shortcode( '[acf field="' . $custom_field_name . '" post_id="' . vcex_get_the_ID() . '"]' );
}

if ( empty( $cf_value ) && 0 !== $cf_value ) {
	$cf_value = get_post_meta( vcex_get_the_ID(), $custom_field_name, true );
	if ( $cf_value && is_string( $cf_value ) ) {
		$cf_value = wp_kses_post( $cf_value );
	}
}

if ( empty( $cf_value ) && 0 !== $cf_value && ! empty( $atts['fallback'] ) ) {
	$cf_value = $atts['fallback'];
}

if ( empty( $cf_value ) || ! is_string( $cf_value ) ) {
	return;
}

// Define classes.
$shortcode_class = array(
	'vcex-custom-field',
	'vcex-module',
	'wpex-clr',
);

if ( $atts['align'] ) {
	$shortcode_class[] = 'text' . sanitize_html_class( $atts['align'] );
}

if ( 'true' == $atts['italic'] ) {
	$shortcode_class[] = 'wpex-italic';
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, $shortcode_tag );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, $shortcode_tag, $atts );

// Inline style.
$shortcode_style = vcex_inline_style( array(
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'color'              => $atts['color'],
	'font_family'        => $atts['font_family'],
	'font_size'          => $atts['font_size'],
	'line_height'        => $atts['line_height'],
	'letter_spacing'     => $atts['letter_spacing'],
	'font_weight'        => $atts['font_weight'],
	'text_transform'     => $atts['text_transform'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), false );

// Shortcode attributes.
$shortcode_attrs = array(
	'class' => esc_attr( $shortcode_class ),
	'style' => $shortcode_style,
);

// Get responsive data.
if ( $responsive_data = vcex_get_module_responsive_data( $atts ) ) {
	$shortcode_attrs['data-wpex-rcss'] = $responsive_data;
}

// Shortcode Output.
$output .= '<div' . vcex_parse_html_attributes( $shortcode_attrs ) . '>';

	$icon = vcex_get_icon_class( $atts, 'icon' );

	if ( $icon ) {

		$icon_style = vcex_inline_style( array(
			'color' => $atts['icon_color'],
		) );

		$icon_class = $icon; // can't use sanitize_html_class because it's multiple classes

		if ( ! $atts['icon_side_margin'] ) {
			$atts['icon_side_margin'] = '5';
		}

		$icon_class .= ' wpex-mr-' . absint( $atts['icon_side_margin'] );

		vcex_enqueue_icon_font( $atts['icon_type'], $icon );

		$output .= '<span class="' . esc_attr( $icon_class ) . '" aria-hidden="true"' . $icon_style . '></span> ';

	}

	if ( $atts['before'] ) {
		$output .= '<span class="vcex-custom-field-before wpex-font-bold">' . esc_html( $atts['before'] ) . '</span> ';
	}

	$output .= apply_filters( 'vcex_custom_field_value_output', $cf_value, $atts );

$output .= '</div>';

// @codingStandardsIgnoreLine
echo $output;