<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$current_sub_page = get_query_var( 'easl_event_subpage' );
$current_sub_page2 = get_query_var( 'easl_event_subpage2' );

$draft_or_pending = get_post_status( get_the_ID() ) && in_array( get_post_status( get_the_ID() ), array( 'draft', 'pending', 'auto-draft', 'future' ) );

$menu_bg_color = get_field('menu_background_color');
if(!$menu_bg_color) {
    $menu_bg_color = 'blue';
}
$menu_bg_custom_color = '';
if('custom' == $menu_bg_color) {
    $menu_bg_custom_color = get_field('menu_background_custom_color');
}
$custom_color_style = '';
if($menu_bg_custom_color) {
    $custom_color_style = 'style="background-color: '. $menu_bg_custom_color .'"';
}
?>
<div class="ste-menu-wrap">
    <?php if ( have_rows( 'event_subpages' ) ): ?>
        <ul class="ste-menu ste-menu-<?php echo $menu_bg_color; ?>"<?php echo $custom_color_style; ?>>
            <?php while ( have_rows( 'event_subpages' ) ):
                the_row( 'event_subpages' );
                if ( ! current_user_can( 'edit_posts' ) && 'draft' == get_sub_field( 'status' ) ) {
                    continue;
                }
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
                $current_slug_for_2nd_level = $current_sub_page2;
                if('subpage' != get_sub_field('content_source')) {
	                $current_slug_for_2nd_level = $current_sub_page;
                }
                $include_in_subpage = get_sub_field('include_in_subpage');
                $title_in_subpage = '';
                if($include_in_subpage) {
                    $title_in_subpage = get_sub_field('title_in_subpage');
                    if(!$title_in_subpage) {
                        $title_in_subpage = $title;
                    }
                }
                $sub_menus = easl_get_event_subpages_sub_pages_html(get_sub_field('subpages'), $url, $draft_or_pending, $current_slug_for_2nd_level, $title_in_subpage, ($current_sub_page == $slug));
                $sub_menus_html = '';
                if(!empty($sub_menus['html'])) {
	                $sub_menus_html = $sub_menus['html'];
	                $item_class .= ' ste-has-submenu';
                }
                if(!empty($sub_menus['has_current'])) {
	                $item_class .= ' ste-show-submenu-active';
	                if ( $current_sub_page != $slug ) {
		                $item_class .= ' easl-active';
	                }
                }
                ?>
                <li class="<?php echo $item_class; ?>">
                    <a href="<?php echo esc_url( $url ); ?>"><?php echo $title; ?></a>
                    <?php echo $sub_menus_html; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
    <div class="ste-desktop-content easl-hide-mobile">
        <?php get_template_part( 'partials/event/structured-event/menu-bellow-widgets' ); ?>
        <?php get_template_part( 'partials/event/structured-event/twitter-feed' ); ?>
    </div>
</div>