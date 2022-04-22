<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
global $post;
if ( 0 == $post->post_parent ) {
    $left_menu_id = get_the_ID();
} else {
    $left_menu_id = get_post_ancestors( get_the_ID() );
    $left_menu_id = end( $left_menu_id );
}
if ( have_rows( 'menu_bellow_widgets', $left_menu_id ) ):
    while ( have_rows( 'menu_bellow_widgets', $left_menu_id ) ):
        the_row();
        $widget_type = get_row_layout();
        get_template_part('partials/menu-structured-page/widgets/' . str_replace('_', '-', $widget_type));
        ?>
    <?php endwhile; ?>
<?php endif; ?>
