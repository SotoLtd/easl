<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $url
 * @var $new_tab
 * @var $downloadable
 * @var $color
 * @var $size
 * @var $align
 * @var $show_arrow
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Generic_Button
 * @var $this EASL_VC_Generic_Button
 */
$title        = '';
$url          = '';
$new_tab      = '';
$downloadable = '';
$color        = '';
$size         = '';
$align        = '';
$show_arrow   = '';

$el_id = $el_class = $css_animation = $css = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$title = trim( $title );
$title = wp_kses( $title, array(
	'span'   => array(
		'class' => array(),
		'style' => array(),
	),
	'em'     => array(),
	'strong' => array(),
	'br'     => array(),
) );
$url   = trim( $url );

if ( $title && $url ) {
	$button_classes = array( 'easl-generic-button' );
	if ( EASL_VC_Generic_Button_Container::$active == true ) {
		$align = 'inline';
	}
	$button_classes[] = EASL_VC_Generic_Button::get_color_class( $color );
	$button_classes[] = EASL_VC_Generic_Button::get_size_class( $size );
	$button_classes[] = EASL_VC_Generic_Button::get_align_class( $align );


	$icon_html = '';

	if ( $show_arrow == 'true' ) {
		$icon_html = '<span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span>';
	}


	$css_class = $this->getCSSAnimation( $css_animation );
	$css_class .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
	$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class, $this->getSettings()['base'], $atts );

	if ( $css_class ) {
		$button_classes[] = $css_class;
	}

	$wrapper_attributes = array();
	if ( ! empty( $el_id ) ) {
		$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
	}

	if ( count( $button_classes ) > 0 ) {
		$wrapper_attributes[] = 'class="' . implode( ' ', $button_classes ) . '"';
	}

	if ( $new_tab == 'true' ) {
		$wrapper_attributes[] = 'target="_blank"';
	}

	if ( $downloadable == 'true' ) {
		$wrapper_attributes[] = 'download="' . basename( parse_url( $url, PHP_URL_PATH ) ) . '"';
		$icon_html            = '<span class="easl-generic-button-icon"><span class="ticon ticon-download"></span></span>';
	}

	$wrapper_attributes[] = 'href="' . esc_url( $url ) . '"';

	$output = '';
	if ( $align == 'center' ) {
		$output .= '<div class="easl-generic-button-wrap easl-content-center">';
	}

	$output .= '<a ' . implode( ' ', $wrapper_attributes ) . '>' . $title . $icon_html . '</a>';

	if ( $align == 'center' ) {
		$output .= '</div>';
	}
	echo $output;
}