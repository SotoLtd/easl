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
$el_class    = '';
$el_id       = '';
$css         = '';
$orientation = '';
try {
    $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
} catch ( Exception $e ) {
    unset( $e );
}

extract( $atts );

$css_class = 'wpb_easl_clock ' . $this->easlGetCssClass( $el_class, $css, $atts );

if ( ! $orientation ) {
    $orientation = 'landscape';
}

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );

$target_time        = get_field( 'easl_clock_target_time', 'option', false );

if ( $target_time ):
    EASL_VC_EASL_Clock::enqueueClockAssets();
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <?php easl_get_template_part('easl-clock.php', array('orientation' => $orientation)) ?>
    </div>
<?php endif; ?>