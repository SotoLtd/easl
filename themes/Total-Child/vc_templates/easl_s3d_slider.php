<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_Carousel
 * @var $this EASL_VC_S3D_Slider
 */
$show_arrows = $el_class = $el_id = $css_animation = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$this->reset_items_data();
EASL_VC_S3D_Slider::$data = $atts;
// It is required to be before to get slide items data
$prepareContent = wpb_js_remove_wpautop( $content, false );


$class_to_filter = 'wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$wrapper_attributes[] = 'class="easl-s3dslider clr ' . esc_attr( trim( $css_class ) ) . '"';


$output = '';
if(EASL_VC_S3D_Slider::$items_count > 0):
	$this->enqueue_fronend_assets();
	$show_arrows = true;
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
	<div id="ss-holder" class="ss-holder">
		<div id="effects">
			<article id="articlehold">
				<?php echo $prepareContent; ?>
			</article>
			<?php if($show_arrows): ?>
			<div class="ss-nav-arrows-next">
				<i id="next-arrow" class="icon-angle-right "></i>
			</div>
			<div class="ss-nav-arrows-prev" style="">
				<i id="prev-arrow" class="icon-angle-left"></i>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="ss-holder">
		<div class="inifiniteLoader animated fadeOutDown">
			<div class="bar"> <span></span>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>