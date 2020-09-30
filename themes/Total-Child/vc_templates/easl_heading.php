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
$text          = '';
$type          = '';
$color         = '';
$link          = '';
$new_tab       = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation   = $this->getCSSAnimation( $css_animation );
$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$css_class = 'easl-heading-wrap ' . $css_class;

$css_class .= in_array( $color, array(
	'primary',
	'secondary',
	'gray'
) ) ? ' easl-color-' . $color : ' easl-color-primary';

$wrapper_attributes = array();
if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
$wrapper_attributes[] = 'class="' . $css_class . '"';
$wrapper_attributes   = implode( ' ', $wrapper_attributes );

$html_tag = in_array( $type, array( 'h1', 'h2', 'h3', 'h4', 'h5' ) ) ? $type : 'h2';

$text = wp_kses( $text, array(
	'br'     => array(),
	'strong' => array(),
	'em'     => array(),
	'span'   => array( 'class' => array() ),
	'sub'    => array(),
	'sup'    => array(),
) );

$inner_html = '';
$target     = '';
if ( $link ) {
	$inner_html = '<a href="' . $link . '"' . $target . '><span>' . $text . '</span></a>';
} else {
	$inner_html = '<span>' . $text . '</span>';
}

if ( $text ):
	?>
	<div <?php echo $wrapper_attributes; ?>>
		<?php echo "<{$html_tag} class='easl-heading'>{$inner_html}</{$html_tag}>"; ?>
	</div>
<?php endif; ?>