<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $button_text_1
 * @var $button_text_2
 * @var $button_link
 * @var $button_link_target
 * @var $button_icon
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Button
 * @var $this EASL_VC_Button
 */
$button_text_1 = $button_text_2 = $button_link = $button_link_target = $button_icon = $el_class = $el_id = $css_animation = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$icon_html = '';
if($button_icon){
	$icon_html = '<img src="'. get_stylesheet_directory_uri() . '/images/button-icons/' . $button_icon .'.png" alt="icon"/>';
}

$button_text = '';
if($button_text_1){
	$button_text .= '<span class="easl-button-text-1">' . $button_text_1 . '</span>';
}
if($button_text_2){
	$button_text .= '<span class="easl-button-text-2">' . $button_text_2 . '</span>';
}

if($button_text && $button_link){
	$button_text = '<a href="'. esc_url($button_link) .'" target="'. $button_link_target .'">'. $icon_html . $button_text . '</a>';
}

$class_to_filter = 'wpb_easl_button wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '';
if($button_text){
	$output = '
		<div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( trim( $css_class ) ) . '">
			<div class="easl-button-wrapper">
				' . $button_text . '
			</div>
		</div>
	';
}

echo $output;