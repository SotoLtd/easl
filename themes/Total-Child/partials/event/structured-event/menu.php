<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$current_sub_page = get_query_var( 'easl_event_subpage' );

$draft_or_pending = get_post_status( get_the_ID() ) && in_array( get_post_status( get_the_ID() ), array( 'draft', 'pending', 'auto-draft', 'future' ) );
?>
<div class="ste-menu-wrap">
    <?php if ( have_rows( 'event_subpages' ) ): ?>
        <ul class="ste-menu">
            <?php while ( have_rows( 'event_subpages' ) ):
                the_row( 'event_subpages' );
                $title = get_sub_field( 'title' );
                if ( ! $title ) {
                    continue;
                }
                $slug = trim( get_sub_field( 'slug' ) );
                $url  = get_permalink();
                if ( $slug ) {
                    if($draft_or_pending) {
                        $url = add_query_arg(array('easl_event_subpage' => $slug), $url);
                    }else{
                        $url = trailingslashit(untrailingslashit( get_permalink() ) . '/' . $slug);
                    }
                }

                $item_class = 'easl-ste-menu-item';
                if ( $current_sub_page == $slug ) {
                    $item_class .= ' easl-active';
                }
                ?>
                <li class="<?php echo $item_class; ?>">
                    <a href="<?php echo esc_url( $url ); ?>"><?php echo $title; ?></a></li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
    <?php if ( have_rows( 'ste_menu_bellow_widgets' ) ) {
        while ( have_rows( 'ste_menu_bellow_widgets' ) ) {
            the_row();
            $widget_type = get_sub_field( 'type' );
            if ( $widget_type ) {
                $widget_type = str_replace( '_', '-', $widget_type );
                get_template_part( 'partials/event/widgets/' . $widget_type );
            }
        }
    } ?>
</div>