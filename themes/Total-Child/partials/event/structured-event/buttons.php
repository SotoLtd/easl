<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$event_id           = wpex_get_the_id();
$event_submit_abstract_url = trim( get_field( 'event_submit_abstract_url', $event_id ) );
$event_register_url        = trim( get_field( 'event_register_url', $event_id ) );
$event_application_url     = trim( get_field( 'event_application_url', $event_id ) );

$event_submit_abstract_title = trim( get_field( 'event_submit_abstract_title', $event_id ) );
$event_register_title        = trim( get_field( 'event_register_title', $event_id ) );

if ( ! $event_submit_abstract_title ) {
    $event_submit_abstract_title = __( 'Submit Abstract', 'total-child' );
}
if ( ! $event_register_title ) {
    $event_register_title = __( 'Register', 'total-child' );
}

$event_abs_btn_sch_type   = get_field( 'event_abs_btn_sch_type', $event_id );
$event_abs_btn_sch_date_1 = get_field( 'event_abs_btn_sch_date_1', $event_id );
$event_abs_btn_sch_date_2 = get_field( 'event_abs_btn_sch_date_2', $event_id );
$event_reg_btn_sch_type   = get_field( 'event_reg_btn_sch_type', $event_id );
$event_reg_btn_sch_date_1 = get_field( 'event_reg_btn_sch_date_1', $event_id );
$event_reg_btn_sch_date_2 = get_field( 'event_reg_btn_sch_date_2', $event_id );

$abstract_button_show = easl_validate_schedule( $event_abs_btn_sch_type, $event_abs_btn_sch_date_1, $event_abs_btn_sch_date_2 );
$register_button_show = easl_validate_schedule( $event_reg_btn_sch_type, $event_reg_btn_sch_date_1, $event_reg_btn_sch_date_2 );


$event_online_programme_url = get_field( 'event_online_programme_url' );
$event_time_type            = easl_get_event_time_type( get_the_ID() );
$event_start_date           = get_post_meta( get_the_ID(), 'event_start_date', true );
$event_end_date             = get_post_meta( get_the_ID(), 'event_end_date', true );
$event_location_display     = easl_get_formatted_event_location( get_the_ID() );
?>
<?php if ( ( $abstract_button_show && $event_submit_abstract_url ) || ( $event_register_url && $register_button_show ) ): ?>

	<div class="ste-buttons-wrap">
		<?php if ( $abstract_button_show && $event_submit_abstract_url ): ?>
			<a class="event-button event-button-icon event-button-icon-application" href="<?php echo esc_url( $event_submit_abstract_url ); ?>" target="_blank"><?php echo $event_submit_abstract_title; ?></a>
		<?php endif; ?>
		<?php if ( $event_register_url && $register_button_show ): ?>
			<a class="event-button event-button-icon event-button-icon-person" href="<?php echo esc_url( $event_register_url ); ?>" target="_blank"><?php echo $event_register_title; ?></a>
		<?php endif; ?>
        <?php if ( 'past' != $event_time_type ): ?>
            <div title="Add to Calendar" class="addeventatc event-button">
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
        <?php endif; ?>
	</div>
<?php endif; ?>
