<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( empty( $widget_class ) ) {
	$widget_class    = 'event-widget-image-box';
	$widget_image   = get_sub_field( 'image' );
	$widget_title   = get_sub_field( 'title' );
	$widget_link    = get_sub_field( 'link' );
	$widget_link_nt = get_sub_field( 'link_nt' );
	if ( $widget_link_nt ) {
		$widget_link_nt_attr = ' target="_blank"';
	}
	$widget_image_src = wp_get_attachment_image_src( $widget_image, 'full' );
}
if ( $widget_image_src ):
	?>
    <div class="event-sidebar-item <?php echo $widget_class; ?>">
        <a class="easl-image-link" href="<?php echo esc_url( $widget_link ); ?>"<?php echo $widget_link_nt_attr; ?>>
			<?php if ( $widget_image_src ): ?>
                <img alt="" src="<?php echo $widget_image_src[0]; ?>"/>
			<?php endif; ?>
            <span><?php echo esc_html( $widget_title ); ?></span>
        </a>
    </div>
<?php endif; ?>