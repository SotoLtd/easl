<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Post_Series_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_post_series shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Vcex_Post_Series {

	/**
	 * Our single instance.
	 */
	private static $instance;

	/**
	 * Disable instantiation.
	 */
	private function __construct() {}

	/**
	 * Disable the cloning of this class.
	 *
	 * @return void
	 */
	final public function __clone() {}

	/**
	 * Disable the wakeup of this class.
	 */
	final public function __wakeup() {}

	/**
	 * Create or retrieve the class instance.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new Vcex_Post_Series;
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {
		add_action( 'vc_after_mapping', array( $this, 'vc_after_mapping' ) );
	}

	/**
	 * Run functions on vc_after_mapping hook.
	 */
	public function vc_after_mapping() {
		vc_lean_map( 'vcex_post_series', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'                    => esc_html__( 'Post Series', 'total-theme-core' ),
			'description'             => esc_html__( 'Display your post series.', 'total-theme-core' ),
			'base'                    => 'vcex_post_series',
			'icon'                    => 'vcex-post-series vcex-icon ticon ticon-pencil',
			'category'                => vcex_shortcodes_branding(),
			'show_settings_on_create' => false,
			'params'                  => array(
				array(
					'type'       => 'vcex_notice',
					'param_name' => 'main_notice',
					'text'       => esc_html__( 'This module displays your post series as defined via the theme template parts so there aren\'t any individual settings.', 'total-theme-core' ),
				),
			)
		);
	}

}