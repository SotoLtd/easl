<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
if ( empty( $widget_class ) ) {
    $widget_class        = 'ste-widget-image-box';
    $widget_title        = get_sub_field( 'widget_title' );
    $widget_image        = get_sub_field( 'image' );
    $widget_link         = get_sub_field( 'link' );
    $widget_link_nt_attr = '';
    $widget_link_nt      = get_sub_field( 'link_nt' );
    if ( $widget_link_nt ) {
        $widget_link_nt_attr = ' target="_blank"';
    }
    $widget_image_src = wp_get_attachment_image_src( $widget_image, 'medium' );
}
if ( $widget_image_src ):
    ?>
    <div class="ste-sidebar-item <?php echo $widget_class; ?>">
        <?php if ( $widget_link ): ?>
            <a class="easl-image-link" href="<?php echo esc_url( $widget_link ); ?>"<?php echo $widget_link_nt_attr; ?>>
                <img alt="" src="<?php echo $widget_image_src[0]; ?>"/>
                <?php if ( $widget_title ): ?>
                    <span><?php echo esc_html( $widget_title ); ?></span>
                <?php endif; ?>
            </a>
        <?php else: ?>
            <div class="easl-image-link">
                <img alt="" src="<?php echo $widget_image_src[0]; ?>"/>
                <?php if ( $widget_title ): ?>
                    <span><?php echo esc_html( $widget_title ); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>