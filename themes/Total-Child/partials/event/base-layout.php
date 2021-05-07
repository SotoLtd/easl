<?php
/**
 * Single event layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.4.1
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<article id="single-blocks" class="single-event-article entry easl-color-<?php echo easl_get_events_topic_color( get_the_ID() ); ?> clr">
	<?php get_template_part('partials/event/event-single-top'); ?>
    <div class="event-main-section">
        <div class="vc_row wpb_row vc_row-fluid">
	        <?php get_template_part('partials/event/event-single-content'); ?>
	        <?php
            if(easl_regular_event_has_sidebar_content()) {
                get_template_part( 'partials/event/event-single-sidebar' );
            }
	        ?>
        </div>
    </div>
</article><!-- #single-blocks -->