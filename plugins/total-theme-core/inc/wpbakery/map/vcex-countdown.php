<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Countdown_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_countdown shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Vcex_Countdown {

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
			static::$instance = new Vcex_Countdown;
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
		vc_lean_map( 'vcex_countdown', array( $this, 'map' ) );

		vc_add_shortcode_param(
			'vcex_timezones',
			array( 'TotalThemeCore\WPBakery\Params\Time_Zone', 'output' )
		);

	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Countdown', 'total' ),
			'description' => esc_html__( 'Animated countdown clock', 'total' ),
			'base'        => 'vcex_countdown',
			'icon'        => 'vcex-countdown vcex-icon ticon ticon-clock-o',
			'category'    => vcex_shortcodes_branding(),
			'params'      => VCEX_Countdown_Shortcode::get_params(),
		);
	}

}