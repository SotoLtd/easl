<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $content
 * Shortcode class EASL_VC_Misc_List
 * @var $this EASL_VC_Misc_List
 */

$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$icon          = '';

$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
EASL_VC_Misc_List::reset_list_items();
$title = trim($title);
if ( $icon ) {
	$icon = str_replace( 'fa-', '', $icon );
	$icon = str_replace( 'fa ', '', $icon );
	$icon = 'ticon ticon-' . $icon;
}

$list_html = wpb_js_remove_wpautop( $content, false );

$class_to_filter = 'easl-misc-list-wrap wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
if(EASL_VC_Misc_List::has_list_items()):
	$icon_html = '';
	if($icon){
		$icon_html = '<i class="'. $icon .'"></i>';
	}
?>
<div <?php echo implode( ' ', $wrapper_attributes ) ; ?>>
	<?php if($title): ?>
	<div class="easl-misc-list-header">
		<h4><?php echo $icon_html; ?><span><?php echo $title; ?></span></h4>
	</div>
	<?php endif; ?>
	<ul class="easl-misc-list">
		<?php echo $list_html; ?>
	</ul>
</div>
<?php endif; ?>
