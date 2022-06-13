<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

final class Register_Scripts {

	/**
	 * Our single Register_Scripts instance.
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
	 * Create or retrieve the instance of Register_Scripts.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new Register_Scripts;
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Get things started.
	 */
	public function init_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin' ) );
	}

	/**
	 * Register admin scripts.
	 */
	public function admin() {

		wp_register_script( 'wp-color-picker-alpha',
			TTC_PLUGIN_DIR_URL . 'assets/js/wp-color-picker-alpha.min.js',
			array( 'wp-color-picker' ),
			'2.1.4',
			true
		);

	}

}