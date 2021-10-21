<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class EASL_VC_TV_Iframe
 * @var $this EASL_VC_TV_Iframe
 */
$el_class     = '';
$el_id        = '';
$css          = '';
$iframe_link  = '';
try {
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
} catch ( Exception $e ) {
    unset( $e );
}

extract( $atts );

$css_class = 'easl-tv-iframe-wrap ' . $this->easlGetCssClass( $el_class, $css, $atts );


$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );


if ( $iframe_link ):
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <div class="easl-tv-iframe">
            <div class="easl-tv-iframe-inner">
                <iframe src="<?php echo esc_url( $iframe_link ); ?>" frameborder="0" scrolling="no" allowfullscreen="allowfullscreen" width="100%" height="100%"></iframe>
            </div>
        </div>
    </div>
<?php endif; ?>