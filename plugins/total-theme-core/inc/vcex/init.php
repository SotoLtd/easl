<?php
/**
 * VCEX Shortcodes.
 *
 * The Original "Visual Composer Extension" Plugin by WPExplorer.com reimagined.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

namespace TotalThemeCore\Vcex;

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
			static::$instance->global_classes();
			static::$instance->include_functions();
			static::$instance->register_shortcodes();
		}

		return static::$instance;
	}

	/**
	 * Run global classes.
	 */
	public function global_classes() {
		Scripts::instance();
	}

	/**
	 * Include helper functions.
	 */
	public function include_functions() {
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/deprecated.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/shortcodes-list.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/core.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/field-description.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/shortcode-atts.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/arrays.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/sanitize.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/grid-filter.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/loadmore.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/entry-classes.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/font-icons.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/onclick.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/scripts.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/parsers.php';
	}

	/**
	 * Register shortcodes.
	 */
	public function register_shortcodes() {

		$modules = vcex_shortcodes_list();
		$path = TTC_PLUGIN_DIR_PATH . 'inc/vcex/shortcodes/';

		if ( ! empty( $modules ) ) {

			foreach ( $modules as $key => $val ) {

				$file = '';

				if ( is_array( $val ) ) {

					$condition = isset( $val['condition'] ) ? $val['condition'] : true;

					if ( $condition ) {

						if ( isset( $val['file'] ) ) {
							$file = $val['file'];
						} else {
							$file = $path . wp_strip_all_tags( $key ) . '.php';
						}

					}

				} else {

					$file = $path . wp_strip_all_tags( $val ) . '.php';

				}

				if ( $file && file_exists( $file ) ) {
					require_once $file;
				}

			}

		}

	}

}