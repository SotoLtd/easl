<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<article id="single-blocks" class="single-event-article small-event-content entry easl-color-<?php echo easl_get_events_topic_color( get_the_ID() ); ?> clr">
	<div class="event-main-section">
		<div class="ste-wrap">
			<?php get_template_part('partials/event/structured-event/menu'); ?>
			<?php get_template_part('partials/event/structured-event/content'); ?>
		</div>
	</div>
</article><!-- #single-blocks -->
