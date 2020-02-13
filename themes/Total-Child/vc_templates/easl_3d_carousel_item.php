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
 * Shortcode class EASL_VC_3D_Carousel_Item
 * @var $this EASL_VC_3D_Carousel_Item
 */
$title = $link = $link_target = $el_class = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$el_class = trim($el_class);

$img_id = preg_replace( '/[^\d]/', '', $image );
$img = wp_get_attachment_image_src($img_id, 'full');
if($img):
	EASL_VC_3D_Carousel::$items_count++;
	EASL_VC_3D_Carousel::$items_data[] = $atts;
	?>
	<section class="bee3D--slide <?php if($el_class){echo esc_attr($el_class);} ?>">
		<div class="bee3D--inner easl-3dcarousel-slide-content">
			<?php if($title): ?>
				<div class="easl-3dcarousel-slide-title">
					<?php if($link): ?>
					<a href="<?php echo $link; ?>"  <?php if($link_target){echo 'target="'. $link_target .'"';} ?>>
					<?php endif; ?>
						<span href="#"><?php echo esc_html($title); ?></span>
					<?php if($link): ?>
					</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="easl-3dcarousel-slide-image">
				<?php if($link): ?>
				<a href="<?php echo $link; ?>"  <?php if($link_target){echo 'target="'. $link_target .'"';} ?>>
				<?php endif; ?>
					<img src="<?php echo $img[0]; ?>" class="clean-img">
				<?php if($link): ?>
				</a>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>