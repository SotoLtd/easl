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
$menu_sp_enable = get_field( 'menu_sp_enable', $left_menu_id );
$child_pages    = get_pages( [
    'child_of'    => $left_menu_id,
    'sort_column' => 'menu_order',
    'sort_order'  => 'ASC'
] );

$left_menu_page = get_post( $left_menu_id );

$menu_bg_color = get_field( 'menu_background_color', $left_menu_id );
if ( ! $menu_bg_color ) {
    $menu_bg_color = 'blue';
    
}
$main_page_item_class = 'easl-ste-menu-item';
if ( is_page( $left_menu_page ) ) {
    $main_page_item_class = ' easl-active';
}
?>
<ul class="ste-menu ste-menu-<?php echo $menu_bg_color; ?>">
    <li class="<?php echo $main_page_item_class; ?>">
        <a href="<?php echo get_permalink( $left_menu_page ); ?>"><?php echo get_the_title( $left_menu_page ); ?></a>
    </li>
    <?php
    foreach ( $child_pages as $child_page ):
        if ( $child_page->ID != $left_menu_id && $child_page->post_parent != $left_menu_id ) {
            continue;
        }
        $item_class = 'easl-ste-menu-item';
        if ( is_page( $child_page->ID ) ) {
            $item_class .= ' easl-active';
        }
        $sub_pages      = get_page_children( $child_page->ID, $child_pages );
        $sub_pages_html = '';
        foreach ( $sub_pages as $sub_page ) {
            $s_item_class = 'ste-submenu-item';
            if ( is_page( $sub_page->ID ) ) {
                $s_item_class .= ' easl-active';
                $item_class   .= ' ste-show-submenu-active easl-active';
            }
            $sub_pages_html .= '<li class="' . $s_item_class . '"><a href="' . get_permalink( $sub_page->ID ) . '">' . get_the_title( $sub_page ) . '</a></li>';
        }
        if ( $sub_pages_html ) {
            $item_class .= ' ste-has-submenu';
            if ( is_page( $child_page->ID ) ) {
                $item_class   .= ' ste-show-submenu-active';
            }
            
            $sub_pages_html = '<div class="ste-submenu-wrap"><ul class="ste-submenu">' . $sub_pages_html . '</ul></div';
        }
        ?>
        <li class="<?php echo $item_class; ?>">
            <a href="<?php echo get_permalink( $child_page ); ?>"><?php echo get_the_title( $child_page ); ?></a>
            <?php echo $sub_pages_html; ?>
        </li>
    <?php endforeach; ?>
</ul>
