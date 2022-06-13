<?php
/**
 * Helper functions for getting shortcode attributes.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get shortcode attributes.
 */
function vcex_shortcode_atts( $shortcode = '', $atts = '', $class = '' ) {

	// Parse deprecated attributes (must run first).
	if ( is_object( $class ) && is_callable( array( $class, 'parse_deprecated_attributes' ) ) ) {
		$atts = $class::parse_deprecated_attributes( $atts );
	}

	// Fix inline shortcodes - @see WPBakeryShortCode => prepareAtts().
	if ( is_array( $atts ) ) {
		foreach ( $atts as $key => $val ) {
			$atts[ $key ] = str_replace( array(
				'`{`',
				'`}`',
				'``',
			), array(
				'[',
				']',
				'"',
			), $val );
		}
	}

	// Return core WPBakery function if it exists.
	if ( function_exists( 'vc_map_get_attributes' ) ) {
		$atts = vc_map_get_attributes( $shortcode, $atts );
	}

	// Use our own custom parser if WPBakery in't enabled.
	else {
		$atts = shortcode_atts( vcex_shortcode_class_attrs( $class ), $atts, $shortcode );
		$atts = apply_filters( 'vc_map_get_attributes', $atts, $shortcode ); // deprecated in 1.2.8
	}

	// Apply filters.
	$atts = apply_filters( 'vcex_shortcode_atts', $atts, $shortcode );

	// Return shortcode attributes.
	return $atts;
}

/**
 * Returns all shortcode atts and default values.
 */
function vcex_shortcode_class_attrs( $class ) {
	$atts = array();
	if ( is_callable( array( $class, 'get_params' ) ) ) {
		$params = $class::get_params();
	} else {
		$map = $class->map();
		$params = $map['params'];
	}
	if ( $params ) {
		foreach( $params as $k => $v ) {
			$value = '';
			if ( isset( $v[ 'std' ] ) ) {
				$value = $v[ 'std' ];
			} elseif ( isset( $v[ 'value' ] ) ) {
				if ( is_array( $v[ 'value' ] ) ) {
					$value = reset( $v[ 'value' ] );
				} else {
					$value = $v[ 'value' ];
				}
			}
			$atts[ $v[ 'param_name' ] ] = $value;
		}
	}
	return $atts;
}

/**
 * Helper function returns a shortcode attribute with a fallback.
 */
function vcex_shortcode_att( $atts, $att, $default = '' ) {
	return isset( $atts[$att] ) ? $atts[$att] : $default;
}