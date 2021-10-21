<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $table
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Table
 * @var $this EASL_VC_Table
 */
$el_class = $el_id = $css_animation = '';
$table    = '';
$atts     = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$table      = absint( $table );
$table_post = get_post( $table );


$class_to_filter = 'wpb_easl_table wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->easlGetSettings( 'base' ), $atts );


$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
    $wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

if ( $table_post && $table_post->post_content ):
    ?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <div class="easl-table-wrap">
            <?php echo $table_post->post_content; ?>
        </div>
    </div>
<?php endif; ?>