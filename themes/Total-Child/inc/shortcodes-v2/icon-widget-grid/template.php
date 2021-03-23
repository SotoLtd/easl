<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $columns
 * @var $el_class
 * @var $el_id
 * @var $content
 * Shortcode class EASL_VC_Icon_Widget_Grid
 * @var $this EASL_VC_Icon_Widget_Grid
 */
$columns = $el_class = $el_id = $css_animation = '';
$atts    = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if ( ! $columns ) {
    $columns = '3';
}
EASL_VC_Icon_Widget_Grid::set_grid_data($columns);
$inner_widget_icons_html = wpb_js_remove_wpautop( $content, false );
EASL_VC_Icon_Widget_Grid::reset_grid_data();

$class_to_filter = 'wpb_easl_icon_widget_grid wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->easlGetSettings('base'), $atts );

$wrapper_attributes = array();
$el_id              = trim( $el_id );
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

if ( $css_class ) {
    $wrapper_attributes[] = 'class="' . esc_attr( trim( $css_class ) ) . '"';
}

if ( $inner_widget_icons_html ):
    ?>
    <div <?php echo implode( ' ', $wrapper_attributes ) ?>>
        <div class="easl-icon-widget-grid-wrapper easl-row easl-row-col-<?php echo $columns ?> clr">
            <?php echo $inner_widget_icons_html; ?>
        </div>
    </div>
<?php endif; ?>