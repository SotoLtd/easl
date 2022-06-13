<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Login_Form as Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_login_form shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Vcex_Login_form {

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
			static::$instance = new Vcex_Login_form;
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
		vc_lean_map( 'vcex_login_form', array( $this, 'map' ) );
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Login Form', 'total-theme-core' ),
			'description' => esc_html__( 'Adds a WordPress login form', 'total-theme-core' ),
			'base'        => 'vcex_login_form',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex-login-form vcex-icon ticon ticon-unlock-alt',
			'params'      => Shortcode::get_params(),
		);
	}

}