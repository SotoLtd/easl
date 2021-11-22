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
                <li class="event-link-calendar">
                    <div title="Add to Calendar" class="addeventatc">
                        <span class="event-link-item">
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