<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$event_date_parts        = easl_get_event_date_parts( get_the_ID() );
$event_topics_name       = easl_event_topics_name( get_the_ID() );
$event_organisers        = trim( get_field( 'event_organisers' ) );
$event_location_display  = easl_get_formatted_event_location( get_the_ID() );
$event_meeting_type_name = easl_meeting_type_name( get_the_ID() );

$event_submit_abstract_url = trim( get_field( 'event_submit_abstract_url' ) );
$event_register_url        = trim( get_field( 'event_register_url' ) );
$event_application_url     = trim( get_field( 'event_application_url' ) );

$event_submit_abstract_title = trim( get_field( 'event_submit_abstract_title', $event_id ) );
$event_register_title        = trim( get_field( 'event_register_title', $event_id ) );

if ( ! $event_submit_abstract_title ) {
    $event_submit_abstract_title = __( 'Submit Abstract', 'total-child' );
}
if ( ! $event_register_title ) {
    $event_register_title = __( 'Register', 'total-child' );
}

?>
<div class="event-top-section">
    <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">
        <div class="wpb_column vc_column_container vc_col-sm-7">
            <div class="vc_column-inner">
                <div class="event-dates-meta-wrap wpb_wrapper easl-flex-con easl-flex-align-center clr">
					<?php
					if ( $event_date_parts ):
                        $date_parts_format = easl_get_event_date_format(get_the_ID());
						?>
                        <div class="event-dates-wrap">
                            <div class="event-dates">
                                <?php if('Y' == $date_parts_format): ?>
                                    <span class="event-year" style="font-size: 20px;line-height: 20px;margin-top: 25px;"><?php echo $event_date_parts['year']; ?></span>
                                <?php elseif ('mY' == $date_parts_format):?>
                                    <span class="event-mon" style="margin-top: 7px;"><?php echo $event_date_parts['month']; ?></span>
                                    <span class="event-year" style="font-size: 20px;line-height: 20px;" ><?php echo $event_date_parts['year']; ?></span>
                                <?php else: ?>
                                    <span class="event-day"><?php echo $event_date_parts['day']; ?></span>
                                    <span class="event-mon"><?php echo $event_date_parts['month']; ?></span>
                                    <span class="event-year" ><?php echo $event_date_parts['year']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
					<?php endif; ?>
                    <div class="event-meta-wrap easl-flex-one">
						<?php if ( $event_topics_name ): ?>
                            <p class="event-meta">
                                <span class="event-meta-type">Topic:</span>
                                <span class="event-meta-value"><?php echo $event_topics_name; ?></span>
                            </p>
						<?php endif; ?>
						<?php if ( $event_organisers ): ?>
                            <p class="event-meta">
                                <span class="event-meta-type"><?php _e( 'Organised by:', 'total-child' ); ?></span>
                                <span class="event-meta-value"><?php echo esc_html( $event_organisers ); ?></span>
                            </p>
						<?php endif; ?>
						<?php if ( $event_location_display ): ?>
                            <p class="event-meta">
                                <span class="event-meta-type">Location:</span>
                                <span class="event-meta-value"><?php echo $event_location_display; ?></span>
                            </p>
						<?php endif; ?>
						<?php if ( $event_meeting_type_name ): ?>
                            <p class="event-meta">
                                <span class="event-meta-type"><?php _e( 'Meeting Type:', 'total-child' ); ?></span>
                                <span class="event-meta-value"><?php echo $event_meeting_type_name; ?></span>
                            </p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
		<?php if ( $event_submit_abstract_url || $event_register_url || $event_application_url ): ?>
            <div class="wpb_column vc_column_container vc_col-sm-5">
                <div class="vc_column-inner" style="justify-content: center;">
                    <div class="wpb_wrapper">
                        <div class="easl-events-top-buttons">
							<?php if ( $event_application_url ): ?>
                                <a class="event-button event-button-wide event-button-icon event-button-icon-person event-button-apply" href="<?php echo esc_url( $event_application_url ); ?>" target="_blank">Apply</a>
							<?php else: ?>
								<?php if ( $event_submit_abstract_url ): ?>
                                    <a class="event-button event-button-wide event-button-icon event-button-icon-application"  href="<?php echo esc_url( $event_submit_abstract_url ); ?>" target="_blank"><?php echo $event_submit_abstract_title; ?></a>
								<?php endif; ?>
								<?php if ( $event_register_url ): ?>
                                    <a class="event-button event-button-wide event-button-icon event-button-icon-person" href="<?php echo esc_url( $event_register_url ); ?>" target="_blank"><?php echo $event_register_title; ?></a>
								<?php endif; ?>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>
</div>
