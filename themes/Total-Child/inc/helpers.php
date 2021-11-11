<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @param string $sch_type before|after|between|none
 * @param string $sch_date Date1 in d/m/Y format
 * @param string $sch_date2 Date2 in d/m/Y format. Only required for between type
 * @param integer $now the compare timestamp defaults to time()
 *
 * @return bool
 */
function easl_validate_schedule( $sch_type, $sch_date, $sch_date2 = '', $now = '' ) {
	if ( ! $sch_type || ( 'none' == $sch_type ) ) {
		return true;
	}

	$schedule_ok = true;
	if ( ! $now ) {
		$now = time();
	}

	if ( $sch_date ) {
		$sch_date .= ' 00:00:00';
	}
	if ( $sch_date2 ) {
		$sch_date2 .= ' 23:59:59';
	}
	switch ( $sch_type ) {
		case 'before':
			$sch_date = DateTime::createFromFormat( 'd/m/Y H:i:s', $sch_date );
			if ( ( false !== $sch_date ) && ( $now < $sch_date->getTimestamp() ) ) {
				$schedule_ok = true;
			} else {
				$schedule_ok = false;
			}
			break;
		case 'after':
			$sch_date = DateTime::createFromFormat( 'd/m/Y H:i:s', $sch_date );
			if ( ( false !== $sch_date ) && ( $now > $sch_date->getTimestamp() ) ) {
				$schedule_ok = true;
			} else {
				$schedule_ok = false;
			}
			break;
		case 'between':
			$sch_date  = DateTime::createFromFormat( 'd/m/Y H:i:s', $sch_date );
			$sch_date2 = DateTime::createFromFormat( 'd/m/Y H:i:s', $sch_date2 );
			if ( ( false !== $sch_date ) && ( false !== $sch_date2 ) && ( $now > $sch_date->getTimestamp() ) && ( $now < $sch_date2->getTimestamp() ) ) {
				$schedule_ok = true;
			} else {
				$schedule_ok = false;
			}
			break;
	}

	return $schedule_ok;
}

function easl_get_event_date_format($event_id) {
    $format = get_post_meta($event_id, 'event_date_display_format', true);
    if(!in_array($format, array('mY', 'Y'))) {
        $format = '';
    }
    return $format;
}



function easl_get_template_part( $name, $args = array() ) {
    $located = locate_template( 'partials/' . $name );
    if ( ! $located ) {
        return;
    }
    if ( ! empty( $args ) && is_array( $args ) ) {
        extract( $args );
    }
    include $located;
}

function easl_get_the_event_subpage_id($include_parent_subpage = false) {
    if(!is_singular('event')) {
        return null;
    }
    $template_format = get_the_terms(get_the_ID(), EASL_Event_Config::get_format_slug());
    if($template_format) {
        $template_format = $template_format[0]->slug;
    }else{
        $template_format = '';
    }
    if('structured-event' != $template_format) {
        return null;
    }
	$current_sub_page_slug  = get_query_var( 'easl_event_subpage' );
	$current_sub_page2_slug = get_query_var( 'easl_event_subpage2' );
	$events_subpages        = get_field( 'event_subpages', $event_id );
	
	if ( ! $events_subpages ) {
		$events_subpages = array();
	}
	$current_sub_page  = false;
	$current_sub_page2 = false;
	foreach ( $events_subpages as $subpage ) {
		if ( ! $current_sub_page2_slug && ! $subpage['slug'] ) {
			$current_sub_page2 = easl_event_subpage_maybe_found_in_subpage( $subpage, $current_sub_page_slug );
		}
		if ( $current_sub_page2 ) {
			break;
		}
		if ( isset( $subpage['slug'] ) && trim( $subpage['slug'] ) == $current_sub_page_slug ) {
			$current_sub_page = $subpage;
			if ( $current_sub_page2_slug && ! empty( $subpage['subpages'] ) ) {
				$current_sub_page2 = easl_event_subpage_maybe_found_in_subpage( $subpage, $current_sub_page2_slug );
			}
			break;
		}
	}
	$subpage_post = false;
	$subpage2_post = false;
	if($current_sub_page &&  ( 'subpage' == $current_sub_page['content_source'] )) {
		$subpage_post  = get_post( $current_sub_page['subpage'] );
	}
	if($current_sub_page2) {
		$subpage2_post = get_post( $current_sub_page2['subpage'] );
	}
	
	if(!$subpage_post && $subpage2_post) {
		return null;
	}
	if(!$include_parent_subpage) {
		if($subpage2_post) {
			return $subpage2_post->ID;
		}elseif($subpage_post) {
			return $subpage_post->ID;
		}
		return null;
	}
	$ids = [];
	if($subpage_post) {
		$ids[] = $subpage_post->ID;
	}
	if($subpage2_post) {
		$ids[] = $subpage2_post->ID;
	}
	return count($ids) > 0 ? $ids : null;
}

/**
 * @return WP_Post|null
 */
function easl_get_the_event_subpage_post() {
    $current_sub_page = easl_get_the_event_subpage();
    if ( empty( $current_sub_page['subpage'] ) ) {
        return null;
    }
    
    return get_post( $current_sub_page['subpage'] );
}
/**
 * @return WP_Post|null
 */
function easl_get_the_event_subpage() {
    if(!is_singular('event')) {
        return null;
    }
    $event_id = get_queried_object_id();
    $template_format = get_the_terms($event_id, EASL_Event_Config::get_format_slug());
    if($template_format) {
        $template_format = $template_format[0]->slug;
    }else{
        $template_format = '';
    }
    if('structured-event' != $template_format) {
        return null;
    }
    //$current_sub_page_slug = get_query_var( 'easl_event_subpage' );
    //return easl_get_event_subpage_by_slug($event_id, $current_sub_page_slug);
	$events_subpage_slugs = get_field( 'event_subpages', $event_id );
	if ( ! $events_subpage_slugs ) {
		$events_subpage_slugs = array();
	}
	$found_subpage   = false;
	$found_subpage2 = false;
	$subpage_request = get_query_var( 'easl_event_subpage' );
	$subpage2_request = get_query_var( 'easl_event_subpage2' );
	foreach ( $events_subpage_slugs as $subpage ) {
		if(!$subpage2_request && !$subpage['slug']) {
			$found_subpage = easl_event_subpage_maybe_found_in_subpage($subpage, $subpage_request);
		}
		if(!current_user_can('edit_posts' ) && !empty($subpage['status']) && 'draft' == $subpage['status']) {
			continue;
		}
		if ( ! empty( $subpage['slug'] ) && trim( $subpage['slug'] ) == $subpage_request ) {
			if($subpage2_request) {
                $found_subpage = true;
				$found_subpage2 = easl_event_subpage_maybe_found_in_subpage($subpage, $subpage2_request);
			}else{
                $found_subpage = $subpage;
            }
			break;
		}
	}
	if ( ! $found_subpage ) {
		return null;
	}
	if ( $subpage2_request && $found_subpage2 ) {
		return $found_subpage2;
	}
	return $found_subpage;
}


function easl_get_event_subpage_by_slug($event_id, $current_sub_page_slug) {
    
    $events_subpages       = get_field( 'event_subpages', $event_id );
    if ( ! $events_subpages ) {
        $events_subpages = array();
    }
    $current_sub_page = false;
    foreach ( $events_subpages as $subpage ) {
        if ( isset( $subpage['slug'] ) && trim( $subpage['slug'] ) == $current_sub_page_slug ) {
            $current_sub_page = $subpage;
            break;
        }
    }
    if(!$current_sub_page) {
        return null;
    }
    if ( 'subpage' != $current_sub_page['content_source'] ) {
        return null;
    }
    $subpage_post = get_post( $current_sub_page['subpage'] );
    if ( !$subpage_post ) {
        return null;
    }
    return $subpage_post;
}

function easl_get_event_subpages_sub_pages_html($subpages, $parent_url, $draft_or_pending, $current_slug, $title_in_subpage = '', $is_parent_current = false) {
	$menu_html = '';
	$has_current_item = false;
	if(!$subpages) {
		return '';
	}
	foreach ( $subpages as $subpage ) {
		if(!current_user_can('edit_posts' ) && !empty($subpage['status']) && 'draft' == $subpage['status']) {
			continue;
		}
		if(!$subpage['title']) {
			continue;
		}
		$slug = trim($subpage['slug']);
		if ( $slug ) {
			if($draft_or_pending) {
				$url = add_query_arg(array('easl_event_subpage2' => $slug), $parent_url);
			}else{
				$url = trailingslashit(untrailingslashit( $parent_url ) . '/' . $slug);
			}
		}else{
			$url = $parent_url;
		}
		$item_class = 'ste-submenu-item';
		if ( $current_slug == $slug ) {
			$item_class .= ' easl-active';
			$has_current_item = true;
		}
		$menu_html .= '<li class="'. $item_class .'"><a href="'. esc_url($url) .'">' . $subpage['title'] . '</a></li>';
	}
	if($menu_html) {
	    if($title_in_subpage) {
            $mirror_item_class = 'ste-submenu-item';
	        if(!$has_current_item && $is_parent_current) {
	            $mirror_item_class .= ' easl-active';
                $has_current_item = true;
            }
            $menu_html = '<li class="'. $mirror_item_class .'"><a href="'. esc_url($parent_url) .'">' . $title_in_subpage . '</a></li>' . $menu_html;;
        }
		$menu_html = '<div class="ste-submenu-wrap"><ul class="ste-submenu">' . $menu_html . '</ul></div';
	}
	
	return array(
		'html' => $menu_html,
		'has_current' => $has_current_item,
	);
}

function easl_event_subpage_maybe_found_in_subpage($subpage, $subpage_request) {
	if(empty($subpage['subpages'])) {
		return false;
	}
	$found = false;
	foreach ( $subpage['subpages'] as $subpage ) {
		if(!current_user_can('edit_posts' ) && !empty($subpage['status']) && 'draft' == $subpage['status']) {
			continue;
		}
		if ( ! empty( $subpage['slug'] ) && trim( $subpage['slug'] ) == $subpage_request ) {
			$found = $subpage;
			break;
		}
	}
	return $found;
}