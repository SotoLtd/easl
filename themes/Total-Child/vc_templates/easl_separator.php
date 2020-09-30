<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class EASL_VC_Separator
 * @var $this EASL_VC_Separator
 */
$el_class = '';
$el_id    = '';
$css      = '';
$size     = '';
$color    = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class = 'easl-separator ' . $css_class;

$css_class .= in_array( $color, array(
	'blue',
	'light-blue',
	'red',
	'teal',
	'orange',
	'grey',
	'yellow',
) ) ? ' easl-color-' . $color : ' easl-color-default';

$size = absint( $size );
if ( ! $size ) {
	$size = 2;
}

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes[] = 'style="height:' . $size . 'px;"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );


?>
<div <?php echo $wrapper_attributes; ?>></div>