<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Column_Side_Border_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_column_side_border shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Vcex_Column_Side_Border {

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
			static::$instance = new Vcex_Column_Side_Border;
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
		vc_lean_map( 'vcex_column_side_border', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Column Side Border', 'total-theme-core' ),
			'description' => esc_html__( 'Responsive column side border.', 'total-theme-core' ),
			'base'        => 'vcex_column_side_border',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex-column-separator vcex-icon ticon ticon-arrows-v',
			'params'      => VCEX_Column_Side_Border_Shortcode::get_params(),
		);
	}

}