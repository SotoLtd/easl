<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_MZ_Membership
 * @var $this EASL_VC_MZ_Membership
 */
$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );

$class_to_filter = 'wpb_easl_mz_membership easl-mz-mp-show-details wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$css_animation   = $this->getCSSAnimation( $css_animation );

$inner_css_class = 'easl-mz-membership-inner easl-mz-crm-view easl-mz-loading';
if(!empty($_GET['highlight_errors'])) {
    $inner_css_class .= ' easl-highlight-errors';
}

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
easl_mz_enqueue_datepicker_assets();
easl_mz_enqueue_select_assets();
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="<?php echo $inner_css_class; ?>">

    </div>
</div>
