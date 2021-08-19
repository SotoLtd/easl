<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if(empty($event_id)) {
    $event_id = get_the_ID();
}
$key_dates = array();
$now       = time();
while ( have_rows( 'event_key_deadline_row', $event_id ) ) {
	the_row( 'event_key_deadline_row' );
	$title      = get_sub_field( 'key_date_name' );
	$start_date = trim( get_sub_field( 'event_key_start_date' ) );
	$end_date   = trim( get_sub_field( 'event_key_end_date' ) );
	if ( is_array( $title ) ) {
		$title = 'Other';
	}
	if ( $title == 'Other' ) {
		$title = get_sub_field( 'event_key_deadline_description' );
	}
	$title = trim( $title );
	if ( ! $title ) {
		continue;
	}
	$start_date = DateTime::createFromFormat( 'd/m/Y', $start_date );
	$end_date   = DateTime::createFromFormat( 'd/m/Y', $end_date );
	if ( false === $start_date ) {
		continue;
	}

	$date_parts        = array();
	$date_parts['d'][] = $start_date->format( 'd' );
	$date_parts['m'][] = $start_date->format( 'M' );
	$date_parts['y'][] = $start_date->format( 'Y' );
	if ( false !== $end_date ) {
		$date_parts['d'][] = $end_date->format( 'd' );
		$date_parts['m'][] = $end_date->format( 'M' );
		$date_parts['y'][] = $end_date->format( 'Y' );
	}
	$date_parts['y'] = array_unique( $date_parts['y'] );
	$formatted_date  = '';
	if ( count( $date_parts['y'] ) > 1 ) {
		$formatted_date = "{$date_parts['d'][0]} {$date_parts['m'][0]}, {$date_parts['y'][0]} - {$date_parts['d'][1]} {$date_parts['m'][1]}, {$date_parts['y'][1]}";
	} else {
		$date_parts['m'] = array_unique( $date_parts['m'] );
		if ( count( $date_parts['m'] ) > 1 ) {
			$formatted_date = "{$date_parts['d'][0]} {$date_parts['m'][0]} - {$date_parts['d'][1]} {$date_parts['m'][1]}, {$date_parts['y'][0]}";
		} else {
			$date_parts['d'] = array_unique( $date_parts['d'] );
			$formatted_date = implode( ' - ', $date_parts['d'] ) . " {$date_parts['m'][0]}, {$date_parts['y'][0]}";
		}
	}

	$class = 'key-date-not-expired';
	if ( $start_date->getTimestamp() < $now ) {
		$class = 'key-date-expired';
	}
	$key_dates[] = array(
		'title'         => $title,
		'start'         => $start_date->format( 'd M, Y' ),
		'formatted_date' => $formatted_date,
		'class'         => $class
	);

}
if ( count( $key_dates ) > 0 ):
	?>
    <div class="easl-small-event-sbitem easl-small-event-sbitem-keydates">
        <div class="easl-small-event-sbitem-inner">
            <h3 class="event-sidebar-item-title">Key Dates</h3>
            <div class="easl-key-dates">
                <ul class="easl-key-dates-list">
					<?php
					foreach ( $key_dates as $key_date ):
						?>
                        <li class="<?php echo $key_date['class']; ?>">
                            <span><?php echo $key_date['formatted_date']; ?></span>
                            <span><?php echo $key_date['title']; ?></span>
                        </li>
					<?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>
