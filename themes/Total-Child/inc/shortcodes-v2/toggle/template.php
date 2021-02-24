<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $color
 * @var $open
 * @var $css_animation
 * @var $el_id
 * @var $content - shortcode content
 * @var $css
 * Shortcode class
 * @var EASL_VC_EASL_Toggle $this
 */
$title = $el_class = $color = $open = $has_padding = $css_animation = $css = $el_id = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );


/**
 * @since 4.4
 */
$elementClass = array(
    'base' => apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_toggle', $this->easlGetSettings('base'), $atts ),
    // TODO: check this code, don't know how to get base class names from params
    'color' => ( $color ) ? 'vc_toggle_color_' . $color : '',
    'open' => ( 'true' === $open ) ? 'vc_toggle_active' : '',
    'extra' => $this->getExtraClass( $el_class ),
    'css_animation' => $this->getCSSAnimation( $css_animation ),
    // TODO: remove getCssAnimation as function in helpers
);

$class_to_filter = trim( implode( ' ', $elementClass ) );

$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->easlGetSettings('base'), $atts );
$heading_output = '<h4>' . esc_html( $title ) . '</h4>';
$output = '<div ' . ( isset( $el_id ) && ! empty( $el_id ) ? 'id="' . esc_attr( $el_id ) . '"' : '' ) . ' class="' . esc_attr( $css_class ) . '"><div class="vc_toggle_title">' . $heading_output . '<i class="vc_toggle_icon"></i></div><div class="vc_toggle_content">' . wpb_js_remove_wpautop( apply_filters( 'the_content', $content ), true ) . '</div></div>';

return $output;
