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
 * Shortcode class EASL_VC_Carousel_Item
 * @var $this EASL_VC_S3D_Slider_Item
 */
$title = $link = $link_target = $el_class = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = trim($el_class);

$img_id = preg_replace( '/[^\d]/', '', $image );
$img = wp_get_attachment_image_src($img_id, 'full');
if($img):
	EASL_VC_S3D_Slider::$items_count++;
	EASL_VC_S3D_Slider::$items_data[] = $atts;
?>
<section <?php if($el_class){echo 'class="'. esc_attr($el_class) .'"';} ?>>
	<div class="ss-row turquoise go-anim no-content">
		<?php if($title): ?>
		<div class="ribbon ribbon-title">
			<h2><a href="#">Image Style Slide</a></h2>
		</div>
		<?php endif; ?>
		<div class="hover-effect h-style">
			<?php if($link): ?>
			<a href="<?php echo $link; ?>"  <?php if($link_target){echo 'target="'. $link_target .'"';} ?>>
			<?php endif; ?>
				<img src="<?php echo $img[0]; ?>" class="clean-img">
				<div class="mask"><i class="icon-search"></i>
                    <span class="img-rollover"></span>
                </div>
			<?php if($link): ?>
			</a>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>