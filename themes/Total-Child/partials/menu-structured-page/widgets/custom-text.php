<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$widget_title   = get_sub_field( 'widget_title' );
$widget_content = get_sub_field( 'content' );
$widget_class   = 'ste-widget-custom-text';
if ( $widget_content ):
    ?>
    <div class="ste-sidebar-item <?php echo $widget_class; ?>">
        <?php if ( $widget_title ): ?>
            <h3 class="ste-widget-title"><?php echo $widget_title; ?></h3>
        <?php endif; ?>
        <div class="clearfix">
            <?php echo do_shortcode( $widget_content ); ?>
        </div>
    </div>
<?php endif; ?>