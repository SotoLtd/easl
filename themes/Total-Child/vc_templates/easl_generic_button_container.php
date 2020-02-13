<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $align
 * @var $el_class
 * @var $el_id
 * @var $content
 * Shortcode class EASL_VC_Generic_Button_Container
 * @var $this EASL_VC_Generic_Button_Container
 */
$align = $el_class = $el_id = $css_animation = '';
$atts  = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( ! in_array( $align, array( 'left', 'right', 'center', 'space_between' ) ) ) {
	$align = 'left';
}

EASL_VC_Generic_Button_Container::$active = true;
$buttons_html = wpb_js_remove_wpautop( $content, false );

$class_to_filter = 'wpb_easl_button_grid wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '';
if ( $buttons_html ) {
	$output = '
	<div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( trim( $css_class ) ) . '">
		<div class="easl-generic-buttons-wrap easl-align-' . $align . '">
			' . $buttons_html . '
		</div>
	</div>
';
}
EASL_VC_Generic_Button_Container::$active = false;
echo $output;