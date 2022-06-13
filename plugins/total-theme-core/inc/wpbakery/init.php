<?php
/**
 * WPBakery tweaks and custom shortcodes.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

namespace TotalThemeCore\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Init {

	/**
	 * Our single Init instance.
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
	 * Create or retrieve the instance of Init.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new Init;
			static::$instance->include_functions();
			static::$instance->initiate_classes();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {

		// Global functions.
		add_action( 'vc_before_mapping', array( $this, 'vc_before_mapping' ) );

		// WPBakery Frontend-Editor scripts.
		if ( is_admin() || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) {
			add_action( 'vc_inline_editor_page_view', array( $this, 'frontend_editor_scripts' ), PHP_INT_MAX );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}

	}

	/**
	 * Includes files.
	 */
	public function include_functions() {
		require_once TTC_PLUGIN_DIR_PATH . 'inc/wpbakery/functions/core.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/wpbakery/functions/autocomplete.php';
	}

	/**
	 * Initiate classes.
	 */
	public function initiate_classes() {

		// Custom backend-editor views.
		if ( is_admin() ) {
			Views\Backend_Editor\Image::instance();
			Views\Backend_Editor\Image_Gallery::instance();
			Views\Backend_Editor\Image_Before_After::instance();
		}

		// Allow frontend editor support for templatera.
		if ( is_admin() || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) ) {
			new Templatera\Enable_Frontend;
		}

	}

	/**
	 * Run functions before/needed for VC mapping.
	 */
	public function vc_before_mapping() {

		// Register icon sets.
		add_filter( 'vc_iconpicker-type-ticons', array( 'TotalThemeCore\WPBakery\Iconpicker\Ticons', 'get_icons' ) );

		// Add custom parameters.
		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			require_once TTC_PLUGIN_DIR_PATH . 'inc/wpbakery/add-params.php';
		}

	}

	/**
	 * Editor Scripts.
	 *
	 * @todo move vcex-params.js here if possible.
	 */
	public function frontend_editor_scripts() {

		wp_enqueue_script(
			'vcex-vc_reload',
			TTC_PLUGIN_DIR_URL . 'inc/wpbakery/assets/js/frontend-editor/vcex-vc_reload.min.js',
			array( 'jquery' ),
			TTC_VERSION,
			true
		);

	}

	/**
	 * Admin Scripts.
	 *
	 * @todo move vcex-params.js here if possible.
	 */
	public function admin_scripts( $hook ) {

		$hooks = array(
			'edit.php',
			'post.php',
			'post-new.php',
			'widgets.php',
			'toolset_page_ct-editor', // Support VC widget plugin.
		);

		if ( ! in_array( $hook, $hooks ) ) {
			return;
		}

		wp_enqueue_style(
			'vcex-wpbakery-backend',
			TTC_PLUGIN_DIR_URL . 'inc/wpbakery/assets/css/vcex-wpbakery-backend.css',
			array(),
			TTC_VERSION
		);

	}

}