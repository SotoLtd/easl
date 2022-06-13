<?php
/**
 * Register scripts for use with vcex elements and enqueues global js.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 1.2.9
 */

namespace TotalThemeCore\Vcex;

defined( 'ABSPATH' ) || exit;

final class Scripts {

	/**
	 * Our single Scripts instance.
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
	 * Create or retrieve the instance of Scripts.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new Scripts;
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Class Constructor.
	 */
	public function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_global_script' ), PHP_INT_MAX );
	}

	/**
	 * Register scripts.
	 */
	public function register_scripts() {

		/* Justified Grid */

		wp_register_script(
			'justifiedGallery',
			vcex_asset_url( 'js/lib/jquery.justifiedGallery.min.js' ),
			array( 'jquery' ),
			'3.8.1',
			true
		);

		wp_register_script(
			'vcex-justified-gallery',
			vcex_asset_url( 'js/vcex-justified-gallery.min.js' ),
			array( 'jquery', 'justifiedGallery' ),
			TTC_VERSION,
			true
		);

		wp_register_style(
			'vcex-justified-gallery',
			vcex_asset_url( 'css/vcex-justified-gallery.css' ),
			array(),
			TTC_VERSION
		);

		/* Carousel Scripts */

		wp_register_style(
			'wpex-owl-carousel',
			vcex_asset_url( 'css/wpex-owl-carousel.min.css' ),
			array(),
			'2.3.4'
		);

		wp_register_script(
			'wpex-owl-carousel',
			vcex_asset_url( 'js/lib/wpex-owl-carousel.min.js' ),
			array( 'jquery' ),
			TTC_VERSION,
			true
		);

		wp_localize_script(
			'wpex-owl-carousel',
			'wpexCarouselSettings',
			array(
				'rtl'  => is_rtl(),
				'i18n' => array(
					'NEXT' => esc_html__( 'next slide', 'total-theme-core' ),
					'PREV' => esc_html__( 'previous slide', 'total-theme-core' ),
				),
			)
		);

		wp_register_script(
			'vcex-carousels',
			vcex_asset_url( 'js/vcex-carousels.min.js' ),
			array( 'jquery', 'wpex-owl-carousel', 'imagesloaded' ),
			TTC_VERSION,
			true
		);

	}

	/**
	 * Load global scripts.
	 */
	public function enqueue_global_script() {

		if ( ! apply_filters( 'vcex_enqueue_frontend_js', true ) ) {
			return;
		}

		$deps = array( 'jquery' );

		if ( defined( 'WPEX_THEME_JS_HANDLE' ) ) {
			$deps[] = WPEX_THEME_JS_HANDLE;
		}

		wp_enqueue_script(
			'vcex-shortcodes',
			vcex_asset_url( 'js/vcex-shortcodes.min.js' ),
			$deps,
			TTC_VERSION,
			true
		);

	}

}