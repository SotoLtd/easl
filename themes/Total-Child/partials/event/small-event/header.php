<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$event_id           = wpex_get_the_id();
$featured_image_url = '';
if ( has_post_thumbnail( $event_id ) ) {
	$featured_image_url = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' );
}
$header_style = '';
$img_alt      = '';
if ( $featured_image_url ) {
	$header_style .= "background-image:url('{$featured_image_url}');";
	$img_alt      = trim( strip_tags( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) );
}

$event_date_parts        = easl_get_event_date_parts( $event_id, 'j', 'F', 'Y' );
$event_topics_name       = easl_event_topics_name( $event_id );
$event_organisers        = trim( get_field( 'event_organisers', $event_id ) );
$event_location_display  = easl_get_formatted_event_location( $event_id );
$event_meeting_type_name = easl_meeting_type_name( $event_id );

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

$custom_title = get_post_meta( $event_id, 'wpex_post_title', true );
if ( $custom_title ) {
	$custom_title = wp_kses( $custom_title, array(
		'a'      => array(
			'href'   => array(),
			'title'  => array(),
			'target' => array()
		),
		'span'   => array(
			'class' => array(),
			'style' => array(),
		),
		'br'     => array(),
		'strong' => array(),
		'em'     => array(),
		'sub'    => array(),
		'sup'    => array(),
	) );
} else {
	$custom_title = get_the_title();
}

if ( $header_style ) {
	$header_style = 'style="' . $header_style . '"';
}
?>
<header class="small-events-page-header" <?php echo $header_style; ?>>
	<?php if ( $featured_image_url ): ?>
        <img class="small-event-header-bg-img" alt="<?php echo $img_alt; ?>" src="<?php echo $featured_image_url; ?>">
	<?php endif; ?>
    <div class="small-event-hdaer-inner container">
        <div class="small-event-page-title-wrap">
			<?php if ( $featured_image_url ): ?>
                <img class="small-event-header-bg-img" alt="<?php echo $img_alt; ?>" src="<?php echo $featured_image_url; ?>">
			<?php endif; ?>
            <div class="easl-hps-caption-content">
                <h1 class="page-header-title easl-hsc-easl-hsc-title" itemprop="headline">
                    <span><?php echo $custom_title; ?></span>
                </h1>
				<?php if ( $event_date_parts || $event_location_display ): ?>
                    <h4 class="easl-hsc-easl-hsc-subtitle">
						<?php if ( $event_date_parts ): ?>
                            <span class="small-event-location"><?php echo $event_date_parts['day'] . ' ' . $event_date_parts['month'] . ', ' . $event_date_parts['year']; ?></span>
						<?php endif; ?>
						<?php if ( $event_date_parts && $event_location_display ): ?><br/><?php endif; ?>
						<?php if ( $event_location_display ): ?>
                            <span class="small-event-location"><?php echo $event_location_display; ?></span>
						<?php endif; ?>
                    </h4>
				<?php endif; ?>
				<?php if ( ( $abstract_button_show && $event_submit_abstract_url ) || ( $event_submit_abstract_url && $register_button_show ) ): ?>
                    <p class="easl-hsc-easl-hsc-text">
						<?php
						if ( ( $abstract_button_show && $event_submit_abstract_url ) && ( $event_submit_abstract_url && $register_button_show ) ) {
							echo 'Abstract submission and registration are open now';
						} elseif ( $abstract_button_show && $event_submit_abstract_url ) {
							echo 'Abstract submission is open now';
						} elseif ( $event_submit_abstract_url && $register_button_show ) {
							echo 'Registration is open now';
						}
						?>
                    </p>
                    <div class="easl-hps-caption-cta-wrap">
						<?php if ( $abstract_button_show && $event_submit_abstract_url ): ?>
                            <a class="easl-generic-button easl-color-lightblue" href="<?php echo esc_url( $event_submit_abstract_url ); ?>" target="_blank">Submit
                                Abstract
                                <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
						<?php endif; ?>
						<?php if ( $event_submit_abstract_url && $register_button_show ): ?>
                            <a class="easl-generic-button easl-color-lightblue" href="<?php echo esc_url( $event_register_url ); ?>" target="_blank">Register
                                <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
						<?php endif; ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
</header>
