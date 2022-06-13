<?php
/**
 * WPBakery Param => Subheading.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
namespace TotalThemeCore\WPBakery\Params;

defined( 'ABSPATH' ) || exit;

final class Subheading {

	public static function output( $settings, $value ) {
		return '<div>' . esc_html( $settings['text'] ) . '<input class="wpb_vc_param_value" type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" value=""></div>';
	}

}