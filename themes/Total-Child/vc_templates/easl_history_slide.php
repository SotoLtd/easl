<?php
/**
 * EASL_VC_Staffs
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $el_class
 * @var $el_id
 * @var $this EASL_VC_History_Slide
 */
$el_class      = '';
$css           = '';
$css_animation = '';
$order         = '';
$limit         = '';
$title_type    = '';
$title_image   = '';
$title_text    = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = 'easl-history-slide-wrap';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );


if ( ( $title_type === 'image' ) ) {
	$title_image = preg_replace( '/[^\d]/', '', $title_image );
	$title_image = wp_get_attachment_image_src( $title_image, 'full' );
	$css_class   .= ' easl-history-slide-title-image';
} else {
	$css_class .= ' easl-history-slide-title-text';
}

$wrapper_attributes = array();

if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
// Build Query
$limit = absint( $limit );
if ( ! $limit ) {
	$limit = - 1;
}
if ( in_array( $order, array( 'ASC', 'DESC' ) ) ) {
	$order = 'DESC';
}
$query_args = array(
	'post_type'      => EASL_History_Config::get_slug(),
	'status'         => 'publish',
	'posts_per_page' => $limit,
	'meta_key'       => 'history_year',
	'orderby'        => 'meta_value',
	'order'          => $order,
);

$history_query = new WP_Query( $query_args );

$year_list     = array();
if ( $history_query->have_posts() ):
	$this->front_end_assets();
	$carousel_options = array(
		'arrows' => 'false',
		'dots' => 'false',
		'auto_play' => 'false',
		'auto_height' => 'true',
		'infinite_loop' => 'false',
		'center' => 'false',
		'animation_speed' => 150,
		'items' => 2,
		'items_scroll' => 1,
		'timeout_duration' => 5000,
		'items_margin' => 30,
		'tablet_items' => 1,
		'mobile_landscape_items' => 1,
		'mobile_portrait_items' => 1
	);
	?>

    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <div class="easl-history-slider">
            <div class="easl-history-slider-title">
                <div class="easl-history-slider-title-image-inner">
                    <?php if ( $title_type == 'image' ): ?>
                        <?php if ( $title_image ): ?><img src="<?php echo $title_image[0]; ?>" alt=""><?php endif; ?>
                    <?php else: ?>
                        <?php if ( $title_text ): ?><h5><?php echo $title_text; ?></h5><?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="easl-history-slider-nav">
                    <a href="#" class="easl-history-slider-next"><i class="ticon ticon-angle-right" aria-hidden="true"></i></a>
                    <a href="#" class="easl-history-slider-prev"><i class="ticon ticon-angle-left" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="easl-hisgtory-slider-con">
                <div class="wpex-carousel easl-history-carousel owl-carousel clr" data-wpex-carousel="<?php echo vcex_get_carousel_settings( $carousel_options, 'easl_history_slide' ); ?>">
					<?php
					while ( $history_query->have_posts() ):
						$history_query->the_post();
						$year = get_post_meta( get_the_ID(), 'history_year', true );
						if ( has_post_thumbnail( get_the_ID() ) ) {
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
						}
						if ( $year && $image ):
							$year_list[] = $year;
							?>
                            <div data-year="<?php echo $year; ?>" class="wpex-carousel-slide easl-history-carousel-item" data-items="2" data-slideby="1" data-nav="false" data-dots="false" data-autoplay="false" data-loop="false" data-center="false" data-items-tablet="1" data-items-mobile-landscape="1" data-items-mobile-portrait="1">
                                <div class="easl-history-carousel-item-inner clr">
                                    <div class="easl-history-carousel-item-image">
                                        <img src="<?php echo $image[0]; ?>" alt="">
                                    </div>
                                    <div class="easl-history-carousel-item-content">
                                        <p class="easl-history-carousel-item-year"><?php echo $year; ?></p>
                                        <h3><?php the_title(); ?></h3>
                                        <div class="easl-history-carousel-item-text">
											<?php  the_content(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
						<?php
						endif;
					endwhile;
					?>
                </div>
            </div>
			<?php if ( count( $year_list ) > 0 ): ?>
                <div class="easl-hisgtory-slider-bar-wrapper">
                    <div class="easl-hisgtory-slider-bar-min"><?php echo max( $year_list ); ?></div>
                    <div class="easl-hisgtory-slider-barinner">
                        <div class="easl-hisgtory-slider-bar-handle" data-syearnum="<?php echo count($year_list) ?>">
                            <div class="easl-hisgtory-slider-bar-chandle ui-slider-handle"></div>
                        </div>
                    </div>
                    <div class="easl-hisgtory-slider-bar-max"><?php echo min( $year_list ); ?></div>
                </div>
			<?php endif; ?>
        </div>
    </div>
	<?php
	wp_reset_query();
endif;
?>