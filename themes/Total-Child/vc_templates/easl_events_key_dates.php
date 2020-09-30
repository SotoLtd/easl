<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class EASL_VC_Heading
 * @var $this EASL_VC_Heading
 */
$el_class      = '';
$el_id         = '';
$css           = '';
$css_animation = '';
$event_id      = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation   = $this->getCSSAnimation( $css_animation );
$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class = 'easl-events-key-dates ' . $css_class;

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );

if ( ! $event_id && is_singular( 'event' ) ) {
    $event_id = get_the_ID();
}

if ( $event_id ):
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <?php
        easl_get_template_part( 'event/global/key-dates.php', array(
            'event_id' => $event_id
        ) )
        ?>
    </div>
<?php endif; ?>