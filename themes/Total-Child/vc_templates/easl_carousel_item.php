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
 * Shortcode class EASL_VC_Carousel_Item
 * @var $this EASL_VC_Carousel_Item
 */
$title = $link = $link_target = $el_class = $el_id = $css_animation = '';
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

$image_html = '';

$img_id = preg_replace( '/[^\d]/', '', $image );
$img = wpb_getImageBySize( array(
			'attach_id' => $img_id,
			'thumb_size' => 'full',
			'class' => 'vc_single_image-img',
		) );

if($img){
	$image_html = '<div class="easl-carousel-image">'. $img['thumbnail'] .'</div>';
}

$title_html = '';
if($title){
	if($link){
		$title = '<a href="'. esc_url($link) .'" target="'. $link_target .'">' . $title . '</a>';
	}
	$title_html = '<h3 class="easl-carousel-title">' . $title . '</h3>';
}
$content_html = '';
if($content){
	$content_html = '<div class="easl-carousel-content">'. wpb_js_remove_wpautop( $content, true ) .'</div>';
}

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$output = '';

	$output = '
		<div ' . implode( ' ', $wrapper_attributes ) . ' class="wpex-carousel-slide easl-carousel-item clr ">
			<div class="easl-carousel-item-inner">
				'. $image_html .'
				'. $title_html .'
				'. $content_html .'
			</div>
		</div>
	';


echo $output;