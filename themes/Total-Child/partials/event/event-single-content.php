<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$event_top_sections            = get_field( 'event_top_sections' );
$event_short_description       = trim( get_field( 'event_short_description' ) );
$event_short_description_title = trim( get_field( 'event_short_description_title' ) );
$event_show_more               = trim( get_field( 'event_show_more' ) );
$event_online_programme_url    = get_field( 'event_online_programme_url' );
$event_time_type               = easl_get_event_time_type( get_the_ID() );
$event_location_display        = easl_get_formatted_event_location( get_the_ID() );
$event_start_date              = get_post_meta( get_the_ID(), 'event_start_date', true );
$event_end_date                = get_post_meta( get_the_ID(), 'event_end_date', true );
$event_notification_url        = get_field( 'event_notification_url' );
$event_website_url             = get_field( 'event_website_url' );
$event_why_attend              = trim( get_field( 'event_why_attend' ) );
$event_why_attend_title        = trim( get_field( 'event_why_attend_title' ) );
$event_who_should_attend       = trim( get_field( 'event_who_should_attend' ) );
$event_who_should_attend_title = trim( get_field( 'event_who_should_attend_title' ) );
$event_topic_covered           = trim( get_field( 'event_topic_covered' ) );
$event_topic_covered_title     = trim( get_field( 'event_topic_covered_title' ) );
$event_highlights              = get_field( 'event_highlights' );
$event_highlights              = wp_parse_args( $event_highlights, array(
	'cover_image' => '',
	'pdf_url'     => '',
) );
$event_sections                = get_field( 'event_sections' );
$about_easl_schools            = get_field( 'about_easl_schools' );
$about_easl_school_title       = wpex_get_mod( 'about_easl_schools_title' );
$about_easl_school_content     = wpex_get_mod( 'about_easl_schools_content' );
$event_accreditation_image     = wpex_get_mod( 'event_accreditation_image' );
$event_accreditation_text      = wpex_get_mod( 'event_accreditation_text' );
$event_bottom_sections         = get_field( 'event_bottom_sections' );
$event_accreditation           = get_field( 'event_accreditation' );

if($event_accreditation_image) {
	$event_accreditation_image = wp_get_attachment_image_url($event_accreditation_image, 'full');
}

$content_col_class = 'wpb_column vc_column_container vc_col-sm-12';
if(easl_regular_event_has_sidebar_content()) {
    $content_col_class = 'wpb_column vc_column_container vc_col-sm-8';
}
?>
<div class="<?php echo $content_col_class; ?>">
    <div class="vc_column-inner">
        <div class="wpb_wrapper clr">
			<?php if ( $event_top_sections && is_array( $event_top_sections ) && count( $event_top_sections ) > 0 ): ?>
                <div class="event-sections">
					<?php
					foreach ( $event_top_sections as $event_top_section ):
						$event_top_section_title = ! empty( $event_top_section['section_title'] ) ? trim( $event_top_section['section_title'] ) : '';
						$event_top_section_content = ! empty( $event_top_section['section_content'] ) ? trim( $event_top_section['section_content'] ) : '';
						if ( ! $event_top_section_content ) {
							continue;
						}
						?>
                        <div class="event-text-block">
							<?php if ( $event_top_section_title ): ?>
                                <h3><?php echo $event_top_section_title; ?></h3>
							<?php endif; ?>
							<?php echo do_shortcode( $event_top_section_content ); ?>
                        </div>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
			<?php if ( $event_short_description || $event_show_more ): ?>
                <div class="event-text-block">
					<?php if ( $event_short_description_title ): ?>
                        <h3><?php echo esc_html( $event_short_description_title ); ?></h3>
					<?php endif; ?>
					<?php if ( $event_short_description ): ?>
                        <div class="event_description">
							<?php echo do_shortcode( $event_short_description ); ?>
                        </div>
					<?php endif; ?>
					<?php if ( $event_show_more ): ?>
                        <div id="event-more-description" class="event-description-more easl-st-collapse">
							<?php echo do_shortcode( $event_show_more ); ?>
                        </div>
                        <p>
                            <a href="#" class="toggle-box-button tbb-hidden" data-target="#event-more-description">
                                <span class="tbb-shown-text">Show more <i class="ticon ticon-angle-down"></i></span>
                                <span class="tbb-hidden-text">Show less <i class="ticon ticon-angle-up"></i></span>
                            </a>
                        </p>
					<?php endif; ?>
                </div>
			<?php endif; ?>
            <div class="event-text-block event-sidebar-item event-links">
                <ul class="event-links-list">
					<?php if ( $event_online_programme_url ): ?>
                        <li class="event-link-program" style="float: left;border: none;margin-right: 40px;">
                            <a class="event-link-item" href="<?php echo esc_url( $event_online_programme_url ); ?>" style="display: inline-block" target="_blank">
                                <span class="event-link-icon"><i class="ticon ticon-list-ul" aria-hidden="true"></i></span>
                                <span class="event-link-text">Scientific Programme</span>
                            </a>
                        </li>
					<?php endif; ?>
                    <?php if ( 'past' != $event_time_type && get_field('atc_enable')): ?>
                        <?php
                        $atc_alt_title = get_field('atc_alt_title');
                        $atc_start_time = get_field('atc_start_time');
                        $atc_end_time = get_field('atc_end_time');
                        $atc_description = get_field('atc_description');
    
                        if(!$atc_alt_title) {
                            $atc_alt_title = get_the_title();
                        }
                        $atc_start_date = '';
                        if ( $event_start_date ){
                            $atc_start_date = date( 'm/d/Y', $event_start_date );
                            if($atc_start_time) {
                                $atc_start_date .= ' ' . $atc_start_time;
                            }
                        }
                        $atc_end_date = '';
                        if ( $event_end_date ){
                            $atc_end_date = date( 'm/d/Y', $event_end_date );
                            if($atc_end_time) {
                                $atc_end_date .= ' ' . $atc_end_time;
                            }
                        }
    
                        ?>
                        <li class="event-link-calendar" style="float: left;border: none;margin-right: 40px">
                            <div title="Add to Calendar" class="addeventatc">
                                <span class="event-link-item" href="" style="display: inline-block">
                                    <span class="event-link-icon"><i class="ticon ticon-calendar-plus-o"
                                                aria-hidden="true"></i></span>
                                    <span class="event-link-text">Add to Calendar</span>
                                </span>
                                <?php if ( $atc_start_date ): ?>
                                    <span class="start"><?php echo $atc_start_date; ?></span>
                                <?php endif; ?>
                                <?php if ( $atc_end_date ): ?>
                                    <span class="end"><?php echo $atc_end_date; ?></span>
                                <?php endif; ?>
                                <span class="date_format">MM/DD/YYYY</span>
                                <?php if ( $atc_description ): ?>
                                    <span class="description"><?php echo $atc_description; ?></span>
                                <?php endif; ?>
                                <span class="timezone">Europe/Zurich</span>
                                <span class="title"><?php echo $atc_alt_title; ?></span>
                                <?php if($event_location_display): ?>
                                    <span class="location"><?php echo $event_location_display; ?></span>
                                <?php endif; ?>
                            </div>
                        </li>
					<?php endif; ?>
					<?php if ( $event_notification_url && ( 'past' != $event_time_type ) ): ?>
                        <li class="event-link-notify" style="float: left;border: none;margin-right: 40px">
                            <a class="event-link-item" href="<?php echo esc_url( $event_notification_url ); ?>" style="display: inline-block" target="_blank">
                                <span class="event-link-icon"><i class="ticon ticon-envelope-o" aria-hidden="true"></i></span>
                                <span class="event-link-text">Get Notified</span>
                            </a>
                        </li>
					<?php endif; ?>
					<?php if ( $event_website_url ): ?>
                        <li class="event-link-website" style="float: left;border: none;margin-right: 40px">
                            <a class="event-link-item" href="<?php echo esc_url( $event_website_url ); ?>" style="display: inline-block" target="_blank">
                                <span class="event-link-icon"><i class="ticon ticon-tv" aria-hidden="true"></i></span>
                                <span class="event-link-text">Visit Website</span>
                            </a>
                        </li>
					<?php endif; ?>
                </ul>
                <div style="clear: both"></div>
            </div>
				<?php if ( $event_why_attend ): ?>
                    <div class="event-text-block">
						<?php if ( $event_why_attend_title ): ?>
                            <h3><?php echo esc_html( $event_why_attend_title ); ?></h3>
						<?php endif; ?>
						<?php echo do_shortcode( $event_why_attend ); ?>
                    </div>
				<?php endif; ?>
				<?php if ( $event_who_should_attend ): ?>
                    <div class="event-text-block">
						<?php if ( $event_who_should_attend_title ): ?>
                            <h3><?php echo esc_html( $event_who_should_attend_title ); ?></h3>
						<?php endif; ?>
						<?php echo do_shortcode( $event_who_should_attend ); ?>
                    </div>
				<?php endif; ?>
			<?php if ( $event_topic_covered ): ?>
                <div class="event-text-block">
					<?php if ( $event_topic_covered_title ): ?>
                        <h3><?php echo esc_html( $event_topic_covered_title ); ?></h3>
					<?php endif; ?>
					<?php echo do_shortcode( $event_topic_covered ); ?>
                </div>
			<?php endif; ?>
			<?php if ( $event_time_type == 'past' && $event_highlights['cover_image'] && $event_highlights['pdf_url'] ): ?>
                <div class="event-text-block">
                    <h3><?php _e( 'Event Highlights', 'total-child' ) ?></h3>
                    <div class="event-highlights-cover">
                        <a href="<?php echo esc_url( $event_highlights['pdf_url'] ); ?>" target="_blank">
                            <img src="<?php echo esc_url( $event_highlights['cover_image'] ); ?>" alt="">
                        </a>
                    </div>
                </div>
			<?php endif; ?>
			<?php if ( $event_sections && is_array( $event_sections ) && count( $event_sections ) > 0 ): ?>
                <div class="event-sections">
					<?php
					foreach ( $event_sections as $event_section ):
						$event_section_title = ! empty( $event_section['section_title'] ) ? trim( $event_section['section_title'] ) : '';
						$event_section_content = ! empty( $event_section['section_content'] ) ? trim( $event_section['section_content'] ) : '';
						if ( ! $event_section_content ) {
							continue;
						}
						?>
                        <div class="event-text-block">
							<?php if ( $event_section_title ): ?>
                                <h3><?php echo $event_section_title; ?></h3>
							<?php endif; ?>
							<?php echo do_shortcode( $event_section_content ); ?>
                        </div>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
			<?php if ( $about_easl_schools && $about_easl_school_content ): ?>
                <div class="event-text-block">
					<?php if ( $about_easl_school_title ): ?>
                        <h3><?php echo $about_easl_school_title; ?></h3>
					<?php endif; ?>
					<?php echo do_shortcode( $about_easl_school_content ); ?>
                </div>
			<?php endif; ?>
			<?php if ( $event_bottom_sections && is_array( $event_bottom_sections ) && count( $event_bottom_sections ) > 0 ): ?>
                <div class="event-sections">
					<?php
					foreach ( $event_bottom_sections as $event_bottom_section ):
						$event_bottom_section_title = ! empty( $event_bottom_section['section_title'] ) ? trim( $event_bottom_section['section_title'] ) : '';
						$event_bottom_section_content = ! empty( $event_bottom_section['section_content'] ) ? trim( $event_bottom_section['section_content'] ) : '';
						if ( ! $event_bottom_section_content ) {
							continue;
						}
						?>
                        <div class="event-text-block">
							<?php if ( $event_bottom_section_title ): ?>
                                <h3><?php echo $event_bottom_section_title; ?></h3>
							<?php endif; ?>
							<?php echo do_shortcode( $event_bottom_section_content ); ?>
                        </div>
					<?php endforeach; ?>
                </div>
			<?php endif; ?>
			<?php if ( $event_accreditation ): ?>
                <div class="event-seperator"></div>
                <div class="event-image-box event-image-box-border-tb">
                    <?php if($event_accreditation_image): ?>
                    <div class="eib-image">
                        <img alt="" src="<?php echo $event_accreditation_image; ?>"/>
                    </div>
                    <?php endif; ?>
                    <?php if($event_accreditation_text): ?>
                    <div class="eib-text">
                        <h3><?php echo $event_accreditation_text; ?></h3>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="event-seperator"></div>
			<?php endif; ?>
        </div>
    </div>
</div>
