<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$event_id           = wpex_get_the_id();
$event_submit_abstract_url = trim( get_field( 'event_submit_abstract_url', $event_id ) );
$event_register_url        = trim( get_field( 'event_register_url', $event_id ) );
$event_application_url     = trim( get_field( 'event_application_url', $event_id ) );

$event_abs_btn_sch_type   = get_field( 'event_abs_btn_sch_type', $event_id );
$event_abs_btn_sch_date_1 = get_field( 'event_abs_btn_sch_date_1', $event_id );
$event_abs_btn_sch_date_2 = get_field( 'event_abs_btn_sch_date_2', $event_id );
$event_reg_btn_sch_type   = get_field( 'event_reg_btn_sch_type', $event_id );
$event_reg_btn_sch_date_1 = get_field( 'event_reg_btn_sch_date_1', $event_id );
$event_reg_btn_sch_date_2 = get_field( 'event_reg_btn_sch_date_2', $event_id );

$abstract_button_show = easl_validate_schedule( $event_abs_btn_sch_type, $event_abs_btn_sch_date_1, $event_abs_btn_sch_date_2 );
$register_button_show = easl_validate_schedule( $event_reg_btn_sch_type, $event_reg_btn_sch_date_1, $event_reg_btn_sch_date_2 );

?>
<?php if ( ( $abstract_button_show && $event_submit_abstract_url ) || ( $event_register_url && $register_button_show ) ): ?>

	<div class="ste-buttons-wrap">
		<?php if ( $abstract_button_show && $event_submit_abstract_url ): ?>
			<a class="event-button event-button-icon event-button-icon-application" style="padding-top: 8px; padding-bottom: 8px;" href="<?php echo esc_url( $event_submit_abstract_url ); ?>" target="_blank">Submit Abstract</a>
		<?php endif; ?>
		<?php if ( $event_register_url && $register_button_show ): ?>
			<a class="event-button event-button-icon event-button-icon-person" style="padding-top: 8px;
                                                      padding-bottom: 8px;" href="<?php echo esc_url( $event_register_url ); ?>" target="_blank">Register</a>
		<?php endif; ?>
	</div>
<?php endif; ?>
