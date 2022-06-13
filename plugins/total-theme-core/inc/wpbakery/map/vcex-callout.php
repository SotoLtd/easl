<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Callout_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_callout shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Vcex_Callout {

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
			static::$instance = new Vcex_Callout;
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
		vc_lean_map( 'vcex_callout', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_callout', array( $this, 'edit_form_fields' ) );
		}

	}

	/**
	 * Update fields on edit.
	 */
	public function edit_form_fields( $atts ) {
		$atts = vcex_parse_icon_param( $atts, 'button_icon_left' );
		$atts = vcex_parse_icon_param( $atts, 'button_icon_right' );
		$atts = VCEX_Callout_Shortcode::parse_deprecated_attributes( $atts );
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Callout', 'total' ),
			'description' => esc_html__( 'Call to action section with or without button', 'total' ),
			'base'        => 'vcex_callout',
			'icon'        => 'vcex-callout vcex-icon ticon ticon-bullhorn',
			'category'    => vcex_shortcodes_branding(),
			'params'      => VCEX_Callout_Shortcode::get_params(),
		);
	}

}