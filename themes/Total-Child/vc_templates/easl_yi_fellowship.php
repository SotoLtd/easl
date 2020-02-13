<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_YI_Fellowship
 * @var $this EASL_VC_YI_Fellowship
 */
$title         = '';
$element_width = '';
$view_all_link = '';
$view_all_url  = '';
$view_all_text = '';
$el_class      = '';
$el_id         = '';
$css_animation = '';
// Shortcode = easl_yi_fellowship
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation = $this->getCSSAnimation( $css_animation );

if ( ! $view_all_text ) {
	$view_all_text = 'View all Events';
}

if ( $title && $view_all_link ) {
	$title .= '<a class="easl-events-all-link" href="' . esc_url( $view_all_url ) . '">' . $view_all_text . '</a>';
}

$class_to_filter = 'wpb_easl_yi_fellowship wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$css_class = trim( $css_class );
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}


$rows = '';

$the_fellowships = new WP_Query( array(
	'post_type'      => 'fellowship',
	'posts_per_page' => - 1,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',

) );
if ( $the_fellowships->have_posts() ): ?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php if ( $title ): ?>
            <h2 class="wpb_headingwpb_easl_widget_heading"><?php echo $title; ?></h2>
		<?php endif; ?>
        <div class="easl-yi-fellowship-wrap">
            <div class="easl-yi-fellowship-inner">
				<?php
				while ( $the_fellowships->have_posts() ):

					$the_fellowships->the_post();

					$app_start_1 = get_field( 'aplication_period_start' );
					$app_end_1   = get_field( 'aplication_period_finish' );
					$app_start_2 = get_field( 'second_aplication_period_start' );
					$app_end_2   = get_field( 'second_aplication_period_finish' );
					$apply_url   = get_field( 'apply_url' );

					if ( $app_start_1 ) {
						$app_start_1 = new DateTime( $app_start_1 );
					}
					if ( $app_end_1 ) {
						$app_end_1 = new DateTime( $app_end_1 );
					}
					if ( $app_start_2 ) {
						$app_start_2 = new DateTime( $app_start_2 );
					}
					if ( $app_end_2 ) {
						$app_end_2 = new DateTime( $app_end_2 );
					}

					$finished = new DateTime( get_field( 'aplication_period_finish' ) );

					$today              = new DateTime( 'now' );
					$application_period = '';
					$thumb_tag          = '';
					$is_finished        = false;
					$is_open            = false;

					$first_aplication_period_start  = date( "d M", strtotime( get_field( 'aplication_period_start' ) ) );
					$first_aplication_period_finish = date( "d M", strtotime( get_field( 'aplication_period_finish' ) ) );

					$second_aplication_period_start  = get_field( 'second_aplication_period_start' ) ? date( "d M", strtotime( get_field( 'second_aplication_period_start' ) ) ) : '';
					$second_aplication_period_finish = get_field( 'second_aplication_period_finish' ) ? date( "d M", strtotime( get_field( 'second_aplication_period_finish' ) ) ) : '';

					$app_period = array();

					if ( ( $app_start_1 && $app_end_1 ) && ( $today > $app_start_1 ) && ( $today < $app_end_1 ) ) {
						$is_open = true;
					}
					if ( ( $app_start_2 && $app_end_2 ) && ( $today > $app_start_2 ) && ( $today < $app_end_2 ) ) {
						$is_open = true;
					}
					if ( $is_open ) {
						if ( $app_start_1 && $app_end_1 ) {
							$app_period[] = $first_aplication_period_start . ' - ' . $first_aplication_period_finish;
						}
						if ( $app_start_2 && $app_end_2 ) {
							$app_period[] = $second_aplication_period_start . ' - ' . $second_aplication_period_finish;
						}
						$app_period = '<span>Application Period: <strong style="display: inline-block;font-weight: inherit;vertical-align: top">' . implode( '<br/>', $app_period ) . '</strong></span>';
					} else {
						$app_period  = '<span style="padding: 10px 54px;color:#333333;">Applications are now closed</span>';
						$is_finished = true;
					}

					?>
                    <div class="yi-fellowship clr">
						<?php if ( has_post_thumbnail() ): ?>
                            <div class="yif-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo wp_get_attachment_image_url( get_post_thumbnail_id(), 'single-post-thumbnail' ); ?>" alt="">
                                </a>
                            </div>
						<?php endif; ?>
                        <div class="yi-content">
                            <div class="yif-title-wrap clr">
                                <h3>
                                    <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                                </h3>
                                <div class="yif-application-preiod"><?php echo $app_period; ?></div>
                            </div>
                            <div class="sp-excerpt"><?php the_excerpt(); ?></div>
                            <a class="easl-generic-button easl-size-medium easl-color-blue" href="<?php the_permalink(); ?>">Find out more <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
							<?php if ( $is_open ): ?>
                                <a class="easl-generic-button easl-size-medium easl-color-blue" style="margin-left: 10px;" href="<?php the_permalink(); ?>"><?php _e( 'Apply Here', 'total-child' ); ?>
                                    <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
							<?php endif; ?>
                        </div>
                    </div>
				<?php endwhile; ?>
            </div>
        </div>
    </div>
	<?php
	wp_reset_query();
endif;
?>