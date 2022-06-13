<?php
namespace TotalThemeCore\WPBakery\Map;
use \VCEX_Button_Shortcode;

defined( 'ABSPATH' ) || exit;

/**
 * Class registers the vcex_button shortcode with the WPBakery page builder.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */
final class Vcex_Button {

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
			static::$instance = new Vcex_Button;
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
		vc_lean_map( 'vcex_button', array( $this, 'map' ) );

		if ( 'vc_edit_form' === vc_post_param( 'action' ) ) {
			add_filter( 'vc_edit_form_fields_attributes_vcex_button', array( $this, 'edit_form_fields' ) );
		}

	}

	/**
	 * Update fields on edit.
	 */
	public function edit_form_fields( $atts ) {
		$atts = VCEX_Button_Shortcode::parse_deprecated_attributes( $atts );
		$atts = vcex_parse_icon_param( $atts, 'icon_left' );
		$atts = vcex_parse_icon_param( $atts, 'icon_right' );
		return $atts;
	}

	/**
	 * Map shortcode via vc_lean_map.
	 */
	public function map() {
		return array(
			'name'        => esc_html__( 'Theme Button', 'total-theme-core' ),
			'description' => esc_html__( 'Customizable button', 'total-theme-core' ),
			'base'        => 'vcex_button',
			'category'    => vcex_shortcodes_branding(),
			'icon'        => 'vcex-total-button vcex-icon ticon ticon-external-link-square',
			'params'      => VCEX_Button_Shortcode::get_params(),
		);
	}

}