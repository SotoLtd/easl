<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="wpb_column vc_column_container vc_col-sm-3">
    <div class="vc_column-inner event-main-sidebar">
        <div class="wpb_wrapper">
            <div class="easl-hide-mobile">
				<?php get_template_part( 'partials/event/small-event/sidebar/topic' ); ?>
				<?php get_template_part( 'partials/event/small-event/sidebar/top-widgets' ); ?>
				<?php get_template_part( 'partials/event/small-event/sidebar/key-dates' ); ?>
				<?php get_template_part( 'partials/event/small-event/sidebar/links' ); ?>
				<?php get_template_part( 'partials/event/small-event/sidebar/countdown' ); ?>
            </div>
			<?php get_template_part( 'partials/event/small-event/sidebar/map' ); ?>
			<?php get_template_part( 'partials/event/small-event/sidebar/poster' ); ?>
			<?php get_template_part( 'partials/event/small-event/sidebar/accreditation' ); ?>
			<?php get_template_part( 'partials/event/small-event/sidebar/contact' ); ?>
        </div>
    </div>
</div>
