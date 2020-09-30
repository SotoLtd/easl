<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$event_online_programme_url = get_field( 'event_online_programme_url' );
$event_time_type            = easl_get_event_time_type( get_the_ID() );
$event_start_date           = get_post_meta( get_the_ID(), 'event_start_date', true );
$event_end_date             = get_post_meta( get_the_ID(), 'event_end_date', true );
$event_location_display     = easl_get_formatted_event_location( get_the_ID() );
$event_notification_url     = get_field( 'event_notification_url' );
$event_website_url          = get_field( 'event_website_url' );
?>
<div class="easl-small-event-sbitem easl-small-event-sbitem-links">
    <div class="easl-small-event-sbitem-inner">
        <ul class="event-links-list">
			<?php if ( $event_online_programme_url ): ?>
                <li class="event-link-program">
                    <a class="event-link-item" href="<?php echo esc_url( $event_online_programme_url ); ?>" target="_blank">
                        <span class="event-link-icon"><i class="ticon ticon-list-ul" aria-hidden="true"></i></span>
                        <span class="event-link-text">Scientific Programme</span>
                    </a>
                </li>
			<?php endif; ?>
			<?php if ( 'past' != $event_time_type ): ?>
                <li class="event-link-calendar">
                    <div title="Add to Calendar" class="addeventatc">
										<span class="event-link-item">
											<span class="event-link-icon"><i class="ticon ticon-calendar-plus-o"
                                                        aria-hidden="true"></i></span>
											<span class="event-link-text">Add to Calendar</span>
										</span>
						<?php if ( $event_start_date ): ?>
                            <span class="start"><?php echo date( 'Y-m-d', $event_start_date ); ?></span>
						<?php endif; ?>
						<?php if ( $event_end_date ): ?>
                            <span class="end"><?php echo date( 'Y-m-d', $event_end_date ); ?></span>
						<?php endif; ?>
                        <span class="timezone">America/Los_Angeles</span>
                        <span class="title"><?php the_title(); ?></span>
                        <span class="location"><?php echo $event_location_display; ?></span>
                    </div>
                </li>
			<?php endif; ?>
			<?php if ( $event_notification_url && ( 'past' != $event_time_type ) ): ?>
                <li class="event-link-notify">
                    <a class="event-link-item" href="<?php echo esc_url( $event_notification_url ); ?>" target="_blank">
                        <span class="event-link-icon"><i class="ticon ticon-envelope-o" aria-hidden="true"></i></span>
                        <span class="event-link-text">Get Notified</span>
                    </a>
                </li>
			<?php endif; ?>
        </ul>
    </div>
</div>