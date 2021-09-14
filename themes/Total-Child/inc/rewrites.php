<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * @param $posts
 * @param WP_Query $query
 */
function easl_event_subpage_checks( $posts, $query ) {
    if ( ! $query->is_main_query() || 'event' != $query->get( 'post_type' ) ) {
        return $posts;
    }

    if ( '' == $query->get( 'easl_event_subpage' ) || ! $posts ) {
        return $posts;
    }

    $events_subpage_slugs = get_field( 'event_subpages', $posts[0]->ID );
    if ( ! $events_subpage_slugs ) {
        $events_subpage_slugs = array();
    }
    $found_subpage   = false;
	$found_subpage2 = false;
    $subpage_request = $query->get( 'easl_event_subpage' );
	$subpage2_request = $query->get( 'easl_event_subpage2' );
    foreach ( $events_subpage_slugs as $subpage ) {
    	if(!$subpage2_request && !$subpage['slug']) {
		    $found_subpage = easl_event_subpage_maybe_found_in_subpage($subpage, $subpage_request);
	    }
        if(!current_user_can('edit_posts' ) && !empty($subpage['status']) && 'draft' == $subpage['status']) {
            continue;
        }
        if ( ! empty( $subpage['slug'] ) && trim( $subpage['slug'] ) == $subpage_request ) {
	        $found_subpage = true;
	        if($subpage2_request) {
		        $found_subpage2 = easl_event_subpage_maybe_found_in_subpage($subpage, $subpage2_request);
	        }
            break;
        }
    }
    if ( ! $found_subpage ) {
        return null;
    }
	if ( $subpage2_request && ! $found_subpage2 ) {
		return null;
	}

    return $posts;
}

add_action( 'posts_results', 'easl_event_subpage_checks', 10, 2 );

function easl_post_rewrite_rules( $post_rewrite ) {
    $modified_rules = array();
    foreach ( $post_rewrite as $match => $redirect ) {
        if ( 'event/[^/]+/([^/]+)/?$' == $match ) {
            $modified_rules['event/([^/]+)/([^/]+)/?$'] = 'index.php?event=$matches[1]&easl_event_subpage=$matches[2]';
            $modified_rules['event/([^/]+)/([^/]+)/([^/]+)/?$'] = 'index.php?event=$matches[1]&easl_event_subpage=$matches[2]&easl_event_subpage2=$matches[3]';
        } else {
            $modified_rules[ $match ] = $redirect;
        }
    }

    return $modified_rules;
}

add_filter( 'rewrite_rules_array', 'easl_post_rewrite_rules', 100 );

function easl_event_subpage_query_vars( $query_vars ) {
    $query_vars[] = 'easl_event_subpage';
    $query_vars[] = 'easl_event_subpage2';

    return $query_vars;
}

add_filter( 'query_vars', 'easl_event_subpage_query_vars', 100 );

/**
 * @param WP_Admin_Bar $wp_admin_bar
 */
function easl_admin_bar_menu_change($wp_admin_bar) {
    if(!is_singular('event')) {
        return;
    }
    $event_id = get_queried_object_id();
    $template_format = get_the_terms($event_id, EASL_Event_Config::get_format_slug());
    if($template_format) {
        $template_format = $template_format[0]->slug;
    }else{
        $template_format = '';
    }
    if('structured-event' != $template_format) {
        return;
    }
    $current_sub_page_slug = get_query_var( 'easl_event_subpage' );
	$current_sub_page2_slug = get_query_var( 'easl_event_subpage2' );
    $events_subpages       = get_field( 'event_subpages', $event_id );
    
    if ( ! $events_subpages ) {
        $events_subpages = array();
    }
    $current_sub_page = false;
	$current_sub_page2 = false;
	$events_subpages2 = array();
    foreach ( $events_subpages as $subpage ) {
	    if(!$current_sub_page2_slug && !$subpage['slug']) {
		    $current_sub_page2 = easl_event_subpage_maybe_found_in_subpage($subpage, $current_sub_page_slug);
	    }
	    if($current_sub_page2) {
	    	break;
	    }
        if ( isset( $subpage['slug'] ) && trim( $subpage['slug'] ) == $current_sub_page_slug ) {
            $current_sub_page = $subpage;
	        if($current_sub_page2_slug && !empty($subpage['subpages'])) {
		        $current_sub_page2 = easl_event_subpage_maybe_found_in_subpage($subpage, $current_sub_page2_slug);
	        }
            break;
        }
    }
    if( (!$current_sub_page || ('subpage' != $current_sub_page['content_source'])) && !$current_sub_page2) {
        return;
    }
    $subpage_post = get_post( $current_sub_page['subpage'] );
    if ( $subpage_post ) {
	
	    $wp_admin_bar->add_menu(
		    array(
			    'id'    => 'edit-event-subpage',
			    'title' => 'Edit Event Subpage',
			    'parent' => 'edit',
			    'href'  => get_edit_post_link( $subpage_post->ID ),
		    )
	    );
    }
	if(!$current_sub_page2) {
		return;
	}
	$subpage2_post = get_post( $current_sub_page2['subpage'] );
	if ( $subpage2_post ) {
		$wp_admin_bar->add_menu(
			array(
				'id'    => 'edit-event-subpage2',
				'title' => 'Edit Event Subpage 2',
				'parent' => 'edit',
				'href'  => get_edit_post_link( $subpage2_post->ID ),
			)
		);
	}
	
}
add_action('admin_bar_menu', 'easl_admin_bar_menu_change');