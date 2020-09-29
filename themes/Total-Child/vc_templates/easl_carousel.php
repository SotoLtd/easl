<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Carousel
 * @var $this EASL_VC_Carousel
 */
$el_class = $el_id = $css_animation = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$items = do_shortcode($content);


$class_to_filter = 'wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$wrapper_attributes[] = 'class="wpex-carousel easl-carousel owl-carousel clr ' . esc_attr( trim( $css_class ) ) . '"';

$carousel_options = array(
	'arrows' => 'false',
	'dots' => true,
	'auto_play' => true,
	'infinite_loop' => true,
	'center' => 'false',
	'animation_speed' => 150,
	'items' => 1,
	'items_scroll' => 1,
	'timeout_duration' => 5000,
	'items_margin' => 0,
	'tablet_items' => 1,
	'mobile_landscape_items' => 1,
	'mobile_portrait_items' => 1
);
$wrapper_attributes[] = 'data-wpex-carousel="'. vcex_get_carousel_settings( $carousel_options, 'easl_carousel' ) .'"';

$output = '';
if($items){
	vcex_enqueue_carousel_scripts();
	$output = '
		<div ' . implode( ' ', $wrapper_attributes ) . '">
			' . $items . '
		</div>
	';
}

echo $output;