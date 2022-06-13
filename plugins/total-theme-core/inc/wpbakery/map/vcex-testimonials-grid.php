<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Testimonials_Grid_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_testimonials_grid shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Vcex_Testimonials_Grid {

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
			static::$instance = new Vcex_Testimonials_Grid;
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
		vc_lean_map( 'vcex_testimonials_grid', array( $this, 'map' ) );

		$vc_action = vc_request_param( 'action' );

		if ( 'vc_get_autocomplete_suggestion' === $vc_action || 'vc_edit_form' === $vc_action ) {

			// Get autocomplete suggestion
			add_filter(
				'vc_autocomplete_vcex_testimonials_grid_include_categories_callback',
				'vcex_suggest_testimonials_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_testimonials_grid_exclude_categories_callback',
				'vcex_suggest_testimonials_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_testimonials_grid_filter_active_category_callback',
				'vcex_suggest_testimonials_categories'
			);

			// Render autocomplete suggestions
			add_filter(
				'vc_autocomplete_vcex_testimonials_grid_include_categories_render',
				'vcex_render_testimonials_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_testimonials_grid_exclude_categories_render',
				'vcex_render_testimonials_categories'
			);
			add_filter(
				'vc_autocomplete_vcex_testimonials_grid_filter_active_category_render',
				'vcex_render_testimonials_categories'
			);

		}
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Testimonials Grid', 'total-theme-core' ),
			'description' => esc_html__( 'Recent testimonials post grid', 'total-theme-core' ),
			'base'        => 'vcex_testimonials_grid',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex-testimonials-grid vcex-icon ticon ticon-comments-o',
			'params'      => VCEX_Testimonials_Grid_Shortcode::get_params(),
		);
	}

}