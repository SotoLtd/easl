<?php
/**
 * vcex_breadcrumbs shortcode output.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

$shortcode_tag = 'vcex_breadcrumbs';

if ( ! vcex_maybe_display_shortcode( $shortcode_tag, $atts ) ) {
	return;
}

// Define empty vars.
$crumbs = $aria = $schema = '';

// Custom crumbs check.
$is_custom = false;

// Yoast breadcrumbs.
if ( function_exists( 'yoast_breadcrumb' )
	&& current_theme_supports( 'yoast-seo-breadcrumbs' )
	&& get_theme_mod( 'enable_yoast_breadcrumbs', true )
) {
	$crumbs = yoast_breadcrumb( '', '', false );
	$is_custom = true;
}

// Custom breadcrumbs.
elseif ( $custom_breadcrumbs = apply_filters( 'wpex_custom_breadcrumbs', null ) ) {
	$crumbs = wp_kses_post( $custom_breadcrumbs );
	$is_custom = true;
}

// Theme breadcrumbs.
elseif ( class_exists( 'WPEX_Breadcrumbs' ) ) {
	$crumbs = new WPEX_Breadcrumbs();
	$crumbs = $crumbs->generate_crumbs(); // needs to generate it's own to prevent issues with theme stuff.
}

// Return if no crumbs.
if ( ! $crumbs ) {
	return;
}

// Get and extract shortcode attributes.
$atts = vcex_shortcode_atts( $shortcode_tag, $atts, $this );

// Load custom font.
if ( ! empty( $atts['font_family'] ) ) {
	vcex_enqueue_font( $atts['font_family'] );
}

// Shortcode classes.
$shortcode_class = array( 'vcex-breadcrumbs' );

$extra_classes = vcex_get_shortcode_extra_classes( $atts, $shortcode_tag );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

$shortcode_class = vcex_parse_shortcode_classes( implode( ' ', $shortcode_class ), $shortcode_tag, $atts );

// Get inline styles.
$shortcode_style = vcex_inline_style( array(
	'color'              => $atts['color'],
	'font_size'          => $atts['font_size'],
	'font_family'        => $atts['font_family'],
	'text_align'         => $atts['align'],
	'line_height'        => $atts['line_height'],
	'letter_spacing'     => $atts['letter_spacing'],
	'background_color'   => $atts['background_color'],
	'border_color'       => $atts['border_color'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
), false );

// Define wrap attributes
$shortcode_attrs = array(
	'class' => $shortcode_class,
	'style' => $shortcode_style,
);

// Responsive settings.
if ( $responsive_data = vcex_get_module_responsive_data( $atts['font_size'], 'font_size' ) ) {
	$shortcode_attrs['data-wpex-rcss'] = $responsive_data;
}

// Get aria tag.
if ( function_exists( 'wpex_get_aria_landmark' ) ) {
	$aria = wpex_get_aria_landmark( 'breadcrumbs' );
}

if ( ! $is_custom ) {
	$schema = ' itemscope itemtype="http://schema.org/BreadcrumbList"';
}

// Display breadcrumbs.
echo '<nav' . vcex_parse_html_attributes( $shortcode_attrs ) . $aria . $schema . '>' . $crumbs . '</nav>';