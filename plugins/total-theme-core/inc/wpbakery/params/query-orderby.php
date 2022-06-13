<?php
/**
 * WPBakery Param => Query Orderby.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

final class Query_Orderby {

	public static function output( $settings, $value ) {

		$output = '<select name="'
				. esc_attr( $settings['param_name'] )
				. '" class="wpb_vc_param_value wpb-input wpb-select '
				. esc_attr( $settings['param_name'] )
				. ' ' . esc_attr( $settings['type'] ) .'">';

		$post_type = isset( $settings['post_type'] ) ? $settings['post_type'] : 'post';

		$options = vcex_orderby_array( $post_type );

		foreach ( $options as $name => $key ) {

			$output .= '<option value="' . esc_attr( $key )  . '" ' . selected( $value, $key, false ) . '>' . esc_attr( $name ) . '</option>';

		}

		$output .= '</select>';

		return $output;

	}

}