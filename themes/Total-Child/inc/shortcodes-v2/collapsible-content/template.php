<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $button_text
 * @var $button_text_close
 * @var $open
 * @var $el_id
 * @var $el_class
 * @var $content - shortcode content
 * @var $css
 * Shortcode class
 * @var EASL_VC_Collapsible_Content $this
 */
$title = $el_class = $color = $open = $has_padding = $css_animation = $css = $el_id = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );
$css_class = 'easl-collapsible-content ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, '', $this->easlGetSettings( 'base' ), $atts ) . vc_shortcode_custom_css_class( $css, ' ' );

if ( empty( $button_text ) ) {
    $button_text = 'Show more';
}
if ( empty( $button_text_close ) ) {
    $button_text = 'Show less';
}
$cc_class = 'easl-st-collapse';
$cc_button_class = 'tbb-hidden';
if ( 'true' == $open ) {
    $cc_class = 'easl-st-collapse-show';
    $cc_button_class = 'tbb-shown';
}

$cc_id         = EASL_VC_Collapsible_Content::generate_id();
$wrapper_attrs = array();
if ( ! empty( $el_id ) ) {
    $wrapper_attrs[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
    $wrapper_attrs[] = 'class="' . esc_attr( $css_class ) . '"';
}
?>

<div <?php echo implode( ' ', $wrapper_attrs ); ?>>
    <div id="<?php echo $cc_id; ?>" class="easl-collapsible-content-inner <?php echo $cc_class; ?>">
        <?php echo wpb_js_remove_wpautop( apply_filters( 'the_content', $content ), true ); ?>
    </div>
    <div class="easl-collapsible-content-button">
        <a href="#" class="toggle-box-button <?php echo $cc_button_class; ?>" data-target="#<?php echo $cc_id; ?>">
            <span class="tbb-shown-text">Show more <i class="ticon ticon-angle-down"></i></span>
            <span class="tbb-hidden-text">Show less <i class="ticon ticon-angle-up"></i></span>
        </a>
    </div>
</div>
