<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$current_sub_page_slug = get_query_var( 'easl_event_subpage' );
$current_sub_page2_slug = get_query_var( 'easl_event_subpage2' );
$events_subpages       = get_field( 'event_subpages', $posts[0]->ID );
if ( ! $events_subpages ) {
    $events_subpages = array();
}
$current_sub_page = false;
foreach ( $events_subpages as $subpage ) {
	if($current_sub_page_slug && !$current_sub_page2_slug && !$subpage['slug']) {
		$current_sub_page = easl_event_subpage_maybe_found_in_subpage($subpage, $current_sub_page_slug);
	}
	if($current_sub_page) {
		$current_sub_page['content_source'] = 'subpage';
	    break;
    }
    if ( isset( $subpage['slug'] ) && trim( $subpage['slug'] ) == $current_sub_page_slug ) {
        if($current_sub_page2_slug){
	        $current_sub_page = easl_event_subpage_maybe_found_in_subpage($subpage, $current_sub_page2_slug);
	        $current_sub_page['content_source'] = 'subpage';
        }else{
	        $current_sub_page = $subpage;
        }
        
        break;
    }
}
?>
<div class="ste-content-wrap">
    <?php get_template_part('partials/event/structured-event/buttons'); ?>
    <?php if ( $current_sub_page ): ?>
        <div class="ste-content">
            <?php
            if ( 'subpage' == $current_sub_page['content_source'] ) {
                $subpage_post = get_post( $current_sub_page['subpage'] );
                if ( $subpage_post ) {
                    echo do_shortcode( $subpage_post->post_content );
                }
            } else {
                the_content();
            }
            ?>
        </div>
    <?php endif; ?>
</div>
<div class="ste-mobile-content easl-hide-desktop">
    <?php get_template_part('partials/event/structured-event/menu-bellow-widgets'); ?>
    <?php get_template_part('partials/event/structured-event/twitter-feed'); ?>
</div>
