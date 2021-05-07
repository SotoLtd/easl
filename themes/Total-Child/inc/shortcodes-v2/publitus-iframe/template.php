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
 * @var $this EASL_VC_EASL_Clock
 */
$el_class     = '';
$el_id        = '';
$css          = '';
$iframe_link  = '';
$new_tab      = '';
try {
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
} catch ( Exception $e ) {
    unset( $e );
}

extract( $atts );

$css_class = 'easl-publitus-iframe-wrap ' . $this->easlGetCssClass( $el_class, $css, $atts );

if ( 'true' == $new_tab ) {
    $css_class .= ' easl-publitus-iframe-lt800';
}

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );


if ( $iframe_link ):
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <div class="easl-publitus-iframe">
            <div class="easl-publitus-iframe-inner">
                <iframe src="<?php echo esc_url( $iframe_link ); ?>"></iframe>
            </div>
        </div>
    </div>
<?php endif; ?>