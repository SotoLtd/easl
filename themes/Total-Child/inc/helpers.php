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

function easl_get_the_event_subpage_id() {
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
    $current_sub_page_slug = get_query_var( 'easl_event_subpage' );
    $events_subpages       = get_field( 'event_subpages' );
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
    return $subpage_post->ID;
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
    $current_sub_page_slug = get_query_var( 'easl_event_subpage' );
    return easl_get_event_subpage_by_slug($event_id, $current_sub_page_slug);
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


function easl_vc_get_brand_colors() {
    return array(
        __( 'Light Blue', 'total-child' ) => 'lightblue',
        __( 'Blue', 'total-child' )       => 'blue',
        __( 'Purple', 'total-child' )     => 'purple',
        __( 'Black', 'total-child' )      => 'black',
        __( 'Grey', 'total-child' )       => 'grey',
        __( 'Red 1', 'total-child' )      => 'red',
        __( 'Red 2', 'total-child' )      => 'red2',
        __( 'Green', 'total-child' )      => 'green',
        __( 'Brown', 'total-child' )      => 'brown',
        __( 'Teal', 'total-child' )       => 'teal',
        __( 'Orange', 'total-child' )     => 'orange',
        __( 'Yellow', 'total-child' )     => 'yellow',
    );
}
