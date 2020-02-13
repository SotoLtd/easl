<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$el_class = $el_id = $css = $css_animation = '';
$align = $html_tag = $bgcolor = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = 'easl-bgtext-box wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );



if(!$bgcolor) {
	$bgcolor = 'blue';
}

$css_class .= ' easl-bg-color-' . $bgcolor . ' easl-text-' . $align;


$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$content = trim(preg_replace( '/<\/?p\>\n?/', "<br/>", $content ));
$content = str_replace(array("\n", "<br />"), array('<br>', ''), $content);


$content = wp_kses($content, array(
	'a' => array('class'=>array(), 'style' => array(), 'target' => array(), 'href' => array(), 'title' => array()),
	'br' => array('class'=>array(), 'style' => array()),
	'em' => array('class'=>array(), 'style' => array()),
	'strong' => array('class'=>array(), 'style' => array()),
	'b' => array('class'=>array(), 'style' => array()),
	'i' => array('class'=>array(), 'style' => array()),
	'sub' => array('class'=>array(), 'style' => array()),
	'sup' => array('class'=>array(), 'style' => array()),
));

if(!$html_tag){
	$html_tag = 'h3';
}
$opening_tag = '<' . $html_tag . ' class="easl-bgtext-box-text">';
$closing_tag = '</' . $html_tag . '>';
if($content):
?>
	<div class="<?php echo esc_attr( $css_class ); ?>" <?php echo implode( ' ', $wrapper_attributes )?>>
		<div class="wpb_wrapper">
			<?php echo $opening_tag . $content.  $closing_tag?>
		</div>
	</div>
<?php endif; ?>