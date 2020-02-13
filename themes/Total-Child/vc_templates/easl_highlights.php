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
 * Shortcode class EASL_Vc_Highlights
 * @var $this EASL_Vc_Highlights
 */

$el_class          = '';
$el_id             = '';
$css_animation     = '';
$css               = '';
$events_link       = '';
$publications_link = '';
$slide_decks_link  = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter    = 'wpb_easl_highlights wpb_content_element';
$class_to_filter    .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class          = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

$now_time           = time();
$event_query        = new WP_Query(
	array(
		'post_type'      => EASL_Event_Config::get_event_slug(),
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'order'          => 'ASC',
		'orderby'        => 'meta_value_num',
		'meta_key'       => 'event_start_date',
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'key'     => 'event_organisation',
				'value'   => 1,
				'compare' => '=',
				'type'    => 'NUMERIC',
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'event_start_date',
					'value'   => $now_time - 86399,
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
				array(
					'key'     => 'event_end_date',
					'value'   => $now_time - 86399,
					'compare' => '>=',
					'type'    => 'NUMERIC',
				),
			),
		)
	)
);
$latest_publication = new WP_Query( array(
	'post_type'      => Publication_Config::get_publication_slug(),
	'post_status'    => 'publish',
	'posts_per_page' => 1,
	'orderby'        => 'meta_value_num',
	'meta_key'       => 'publication_raw_date',
	'order'          => 'DESC',
) );
$latest_slide_desks = new WP_Query( array(
	'post_type'      => Slide_Decks_Config::get_slug(),
	'post_status'    => 'publish',
	'posts_per_page' => 2,
) );

?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="easl-highlights">
        <div class="easl-highlights-inner">
            <div class="easl-highlights-header">
                <h2 class="easl-highlights-title"><?php echo _e( 'Highlights', 'total-child' ); ?></h2>
                <div class="easl-highlights-filter">
                    <h5><?php _e( 'Select your field of expertise:', 'total-child' ); ?></h5>
                    <div class="easl-highlights-filter-dd-wrap">
                        <p class="easl-color-light-blue easl-highlights-filter-label" data-color="light-blue"
                           data-topic=""><?php _e( 'All topics', 'total-child' ); ?></p>
                        <ul class="easl-highlights-filter-dd">
                            <li class="easl-color-light-blue"><a href="#" data-color="light-blue"
                                                                 data-topic="all"><?php _e( 'All topics', 'total-child' ); ?></a>
                            </li>
							<?php
							$topics = get_terms( array(
								'taxonomy'   => EASL_Event_Config::get_topic_slug(),
								'hide_empty' => false,
								'orderby'    => 'name',
								'order'      => 'ASC',
								'exclude'    => array( 787 ),
							) );
							if ( ! is_array( $topics ) ) {
								$topics = array();
							}
							foreach ( $topics as $topic ):
								$topic_color = get_term_meta( $topic->term_id, 'easl_tax_color', true );
								if ( ! $topic_color ) {
									$topic_color = 'blue';
								}
								?>
                                <li class="easl-color-<?php echo $topic_color; ?>"><a href="#"
                                                                                      data-color="<?php echo $topic_color; ?>"
                                                                                      data-topic="<?php echo $topic->slug; ?>"><?php echo $topic->name; ?></a>
                                </li>
							<?php
							endforeach;
							?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="easl-highlights-items">
				<?php if ( $event_query->have_posts() ): ?>
                    <div class="easl-highlights-events">
                        <h5 class="easl-highlights-item-title">
							<?php if ( $events_link ): ?><a
                                    href="<?php echo esc_url( $events_link ); ?>"><?php endif; ?>
                                <span><?php _e( 'Events', 'total-child' ); ?></span>
								<?php if ( $events_link ): ?></a><?php endif; ?>
                        </h5>
                        <div class="easl-events-list-wrap">
                            <ul class="easl-highlights-event-items">
								<?php
								while ( $event_query->have_posts() ) {
									$event_query->the_post();
									get_template_part( 'partials/event/event-loop' );
								}
								wp_reset_query();
								?>
                            </ul>
                        </div>
                    </div>
				<?php endif; ?>
				<?php if ( $latest_publication->have_posts() ): ?>
                    <div class="easl-highlights-publications">
                        <h5 class="easl-highlights-item-title">
							<?php if ( $publications_link ): ?><a
                                    href="<?php echo esc_url( $publications_link ); ?>"><?php endif; ?>
                                <span><?php _e( 'Publications', 'total-child' ); ?></span>
								<?php if ( $publications_link ): ?></a><?php endif; ?>
                        </h5>
                        <div class="easl-highlights-publications-item">
							<?php
							while ( $latest_publication->have_posts() ) {
								$latest_publication->the_post();
								get_template_part( 'partials/highlights/publications' );
							}
							wp_reset_query();
							?>
                        </div>
                    </div>
				<?php endif; ?>
				<?php if ( $latest_slide_desks->have_posts() ): ?>
                    <div class="easl-highlights-slide-decks">
                        <h5 class="easl-highlights-item-title">
							<?php if ( $slide_decks_link ): ?><a
                                    href="<?php echo esc_url( $slide_decks_link ); ?>"><?php endif; ?>
                                <span><?php _e( 'Slide Decks', 'total-child' ); ?></span>
								<?php if ( $slide_decks_link ): ?></a><?php endif; ?>
                        </h5>
                        <ul class="easl-highlights-slide-desks-items">
							<?php
							while ( $latest_slide_desks->have_posts() ) {
								$latest_slide_desks->the_post();
								get_template_part( 'partials/highlights/slide-decks' );
							}
							wp_reset_query();
							?>
                        </ul>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>