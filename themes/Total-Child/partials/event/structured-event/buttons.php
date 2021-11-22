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
        <?php if ( 'past' != $event_time_type && get_field('atc_enable', $event_id) ): ?>
        <?php
                $atc_alt_title = get_field('atc_alt_title', $event_id);
                $atc_start_time = get_field('atc_start_time', $event_id);
                $atc_end_time = get_field('atc_end_time', $event_id);
                $atc_description = get_field('atc_description', $event_id);
                
                if(!$atc_alt_title) {
                    $atc_alt_title = get_the_title($event_id);
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
            <div title="Add to Calendar" class="addeventatc event-button">
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
        <?php endif; ?>
	</div>
<?php endif; ?>
