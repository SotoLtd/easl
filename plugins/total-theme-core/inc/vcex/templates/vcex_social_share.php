<?php
/**
 * vcex_social_share shortcode.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

$shortcode_tag = 'vcex_social_share';

if ( ! vcex_maybe_display_shortcode( $shortcode_tag, $atts ) ) {
	return;
}

if ( ! function_exists( 'wpex_social_share_list' )
	|| ! function_exists( 'wpex_social_share_data' )
	|| ! function_exists( 'wpex_social_share_list' )
) {
	return;
}

$atts = vcex_shortcode_atts( $shortcode_tag, $atts, $this );

if ( ! empty( $atts[ 'sites' ] ) ) {
	$sites = (array) vcex_vc_param_group_parse_atts( $atts[ 'sites' ] );
}

if ( empty( $sites ) || ! is_array( $sites ) ) {
	return;
}

$sites_array = array();
foreach ( $sites as $k => $v ) {
	if ( is_array( $v ) && isset( $v[ 'site' ] ) ) {
		$sites_array[] = $v[ 'site' ];
	}
}

$args = array(
	'position' => 'horizontal',
);

if ( ! empty( $atts['style'] ) ) {
	$args['style'] = $atts['style'];
}

$shortcode_class = array(
	'vcex-module',
	'vcex-social-share'
);

if ( $bottom_margin_class = vcex_parse_margin_class( $atts['bottom_margin'], 'wpex-mb-' ) ) {
	$shortcode_class[] = $bottom_margin_class;
}

if ( $css_animation_class = vcex_get_css_animation( $atts['css_animation'] ) ) {
	$shortcode_class[] = $css_animation_class;
}

$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, $shortcode_tag, $atts );

$inline_style = array(
	'animation_delay' => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
);

?>

<div class="<?php echo esc_attr( $shortcode_class ); ?>"<?php echo vcex_inline_style( $inline_style ); ?>>

	<div <?php wpex_social_share_class( $args ); ?> <?php wpex_social_share_data( vcex_get_the_ID(), $sites_array ); ?>><?php

		wpex_social_share_list( $args, $sites_array );

	?></div>

</div>