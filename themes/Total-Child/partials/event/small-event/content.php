<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="wpb_column vc_column_container vc_col-sm-9">
	<div class="vc_column-inner">
		<div class="wpb_wrapper clr">
			<?php get_template_part('partials/event/small-event/mobile-top-content'); ?>
			<?php the_content(); ?>
		</div>
	</div>
</div>
