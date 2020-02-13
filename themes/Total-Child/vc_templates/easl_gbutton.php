<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $button_text
 * @var $button_link
 * @var $button_link_target
 * @var $button_icon
 * @var $active
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_GButton
 * @var $this EASL_VC_GButton
 */
$button_text = $button_link = $button_link_target = $button_icon = $el_class = $el_id = $css_animation = $active = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$icon_html = '';
if($button_icon){
	$icon_html = '<span class="easl-gbtn-icon easl-gbtn-'. $button_icon .'"></span>';
}

$button_text_html = '';
if($button_text){
	$button_text = '<span class="easl-gbtn-text">' . $button_text . '</span>';
}

if($button_text || $button_icon){
	$button_text_html = '<a class="easl-gbtn" href="'. esc_url($button_link) .'" target="'. $button_link_target .'">'. $icon_html . $button_text . '</a>';
}

$class_to_filter = 'wpb_easl_button wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$button_enabled = true;
if($active == 'false' || false !== strpos($el_class, 'permanently-hidden')){
	$button_enabled = false;
}

$output = '';
if($button_text_html && $button_enabled){
	$output = '
		<div ' . implode( ' ', $wrapper_attributes ) . ' class="easl-gbtn-wrap easl-col ' . esc_attr( trim( $css_class ) ) . '">
			<div class="easl-col-inner">
				' . $button_text_html . '
			</div>
		</div>
	';
}

echo $output;