<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$widget_id  = get_sub_field( 'event_widget' );
$widget_obj = get_post( $widget_id );
if ( $widget_obj && ($widget_obj->post_status == 'publish') ):
	/**
	 * @todo add more widget types
	 */
	$widget_type         = get_field( 'widget_type', $widget_id );
	$widget_image        = get_field( 'widget_image', $widget_id );
	$widget_title        = get_field( 'widget_title', $widget_id );
	$widget_link         = get_field( 'widget_link', $widget_id );
	$widget_link_nt      = get_field( 'widget_link_nt', $widget_id );
	$widget_link_nt_attr = '';
	if ( $widget_link_nt ) {
		$widget_link_nt_attr = ' target="_blank"';
	}
	$widget_image_src = wp_get_attachment_image_src( $widget_image, 'full' );
	$widget_class      = 'event-widget-event-widget event-widget-image-box';
	include locate_template( 'partials/event/widgets/image-box.php' );
	?>
<?php endif; ?>