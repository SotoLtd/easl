<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Woocommerce_Carousel_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_woocommerce_carousel shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Vcex_Woocommerce_Carousel {

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
			static::$instance = new Vcex_Woocommerce_Carousel;
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
		vc_lean_map( 'vcex_woocommerce_carousel', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			add_filter(
				'vc_autocomplete_vcex_woocommerce_carousel_include_categories_callback',
				'vcex_suggest_product_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_woocommerce_carousel_exclude_categories_callback',
				'vcex_suggest_product_categories'
			);

			add_filter(
				'vc_autocomplete_vcex_woocommerce_carousel_include_categories_render',
				'vcex_render_product_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_woocommerce_carousel_exclude_categories_render',
				'vcex_render_product_categories'
			);

		}
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Woo Products Carousel (Custom)', 'total-theme-core' ),
			'description' => esc_html__( 'Custom products carousel', 'total-theme-core' ),
			'base'        => 'vcex_woocommerce_carousel',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex-woocommerce-carousel vcex-icon ticon ticon-shopping-cart',
			'params'      => VCEX_Woocommerce_Carousel_Shortcode::get_params(),
		);
	}

}