<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * Shortcode class EASL_VC_Callout
 * @var $this EASL_VC_Callout
 */
$el_class = '';
$el_id    = '';
$css      = '';

$button_title = '';
$button_url   = '';
$button_nt    = '';
$button_color = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class = 'easl-callout-wrap ' . $css_class;

$button_title = trim( $button_title );
$button_url   = trim( $button_url );

if ( $button_title ) {
	$css_class .= ' easl-callout-has-button';
}


$button_class = 'easl-generic-button easl-generic-button-right-icon easl-size-medium ';
$button_class .= in_array( $button_color, array(
	'blue',
	'light-blue',
	'red',
	'teal',
	'orange',
	'grey',
	'yellow',
) ) ? ' easl-color-' . $button_color : ' easl-color-light-blue';

if ( 'true' == $button_nt ) {
	$button_nt = ' target="_blank"';
} else {
	$button_nt = '';
}

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );

$content = wpb_js_remove_wpautop( $content );
if ( $content ):
	?>
    <div <?php echo $wrapper_attributes; ?>>
        <div class="easl-callout">
            <div class="easl-callout-text">
                <div class="easl-callout-text-inner"><?php echo $content; ?></div>
            </div>
			<?php if ( $button_title ): ?>
                <div class="easl-callout-button">
                    <div class="easl-callout-button-inner">
                        <a href="<?php echo $button_url; ?>" class="<?php echo $button_class; ?>"<?php echo $button_nt; ?>><?php echo $button_title; ?>
                            <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>
<?php endif; ?>