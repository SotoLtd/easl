<?php
namespace TotalThemeCore\Shortcodes;

defined( 'ABSPATH' ) || exit;

final class Shortcode_Site_Url {

	public function __construct() {

		if ( ! shortcode_exists( 'site_url' ) ) {
			add_shortcode( 'site_url', array( __CLASS__, 'output' ) );
		}

	}

	public static function output( $atts, $content = '' ) {

		$atts = shortcode_atts( array(
			'path'   => '',
			'scheme' => null,
		), $atts, 'site_url' );

		return site_url( $atts['path'], $atts['scheme'] );

	}

}