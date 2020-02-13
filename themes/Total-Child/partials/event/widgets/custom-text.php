<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( empty( $widget_class ) ) {
	$widget_class = 'event-widget-custom-text';
	$widget_custom_text = get_sub_field('custom_text');
}
if ( $widget_custom_text ):
	?>
	<div class="event-sidebar-item <?php echo $widget_class; ?>">
		<div class="clearfix">
			<?php echo do_shortcode($widget_custom_text); ?>
		</div>
	</div>
<?php endif; ?>