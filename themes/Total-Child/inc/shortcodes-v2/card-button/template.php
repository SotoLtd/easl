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
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Card_Button
 * @var $this EASL_VC_Card_Button
 */
$button_text = $button_link = $button_link_target = $button_icon = $el_class = $el_id = $css_animation = '';
$atts        = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$icon_html = '';
if ( $button_icon ) {
    $icon_html = '<div class="easl-card-btn-icon-wrapper">' .
                 '<div class="d-flex align-items-center justify-content-center easl-card-block" style="position: relative;">' .
                 '<div class="easl-card-btn-icon ' . $button_icon . '-icon"></div>' .
                 '<div class="mask"></div>' .
                 '</div>' .
                 '</div>';
}

$button_text_html = '';
if ( $button_text ) {
    $button_text = '<div class="easl-card-text">' . $button_text . '<i class="ticon ticon-angle-right"></i></div>';
}

if ( $button_text || $button_icon ) {
    $button_text_html = '<a class="easl-card-btn" href="' . esc_url( $button_link ) . '" target="' . $button_link_target . '">' .
                        '<div class="card-btn-wrapper d-flex flex-direction-column align-items-stretch">' . $icon_html . $button_text . '</div></a>';
}

$class_to_filter = 'wpb_easl_button wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = 'easl-card-btn-wrap easl-col easl-card ' . apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->easlGetSettings( 'base' ), $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
    $wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
}

if ( $button_text_html ):
    ?>
    <div <?php echo implode( ' ', $wrapper_attributes ) ?>>
        <div class="easl-col-inner">
            <?php echo $button_text_html ?>
        </div>
    </div>
<?php endif; ?>
