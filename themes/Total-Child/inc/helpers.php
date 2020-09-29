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