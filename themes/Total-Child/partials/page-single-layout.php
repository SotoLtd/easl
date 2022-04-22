<?php
/**
 * Single Page Layout.
 *
 * @package    Total WordPress theme
 * @subpackage Partials
 * @version    5.1
 */

defined( 'ABSPATH' ) || exit;

// Custom template design.
if ( $template_content = wpex_get_singular_template_content( 'page' ) ) {
    wpex_singular_template( $template_content );
    
    return;
}
global $post;
if ( 0 == $post->post_parent ) {
    $left_menu_id = get_the_ID();
} else {
    $left_menu_id = get_post_ancestors( get_the_ID() );
    $left_menu_id = end($left_menu_id);
}
$menu_sp_enable = get_field( 'menu_sp_enable', $left_menu_id );
$child_pages    = get_pages( [
    'child_of'    => $left_menu_id,
    'sort_column' => 'menu_order',
    'sort_order'  => 'ASC'
] );


?>

<article id="single-blocks" <?php wpex_page_single_blocks_class(); ?>>
    <?php if ( $menu_sp_enable && $child_pages ): ?>
    <div class="ste-wrap page-ste-wrap">
        <div class="ste-menu-wrap">
            <?php get_template_part( 'partials/menu-structured-page/menu' ); ?>

            <div class="ste-desktop-content easl-hide-mobile">
                <?php get_template_part( 'partials/menu-structured-page/menu-bellow-widgets' ); ?>
            </div>
        </div>
        <div class="ste-content-wrap">
            <?php endif; ?>
            <?php
            // Get single layout blocks.
            $blocks = wpex_single_blocks();
            
            // Make sure we have blocks.
            if ( ! empty( $blocks ) ) :
                
                // Loop through blocks.
                foreach ( $blocks as $block ) :
                    
                    // Media not needed for this position.
                    if ( 'media' === $block && wpex_has_custom_post_media_position() ) {
                        continue;
                    }
                    
                    // Callable output.
                    if ( 'the_content' !== $block && is_callable( $block ) ) {
                        
                        call_user_func( $block );
                        
                    } // Get block template part.
                    else {
                        
                        get_template_part( 'partials/page-single-' . $block );
                        
                    }
                
                endforeach;
            
            endif; ?>
            <?php if ( $menu_sp_enable && $child_pages ): ?>
        </div>
        <div class="ste-mobile-content easl-hide-desktop">
            <div class="ste-desktop-content easl-hide-mobile">
                <?php get_template_part( 'partials/menu-structured-page/menu-bellow-widgets' ); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
</article>