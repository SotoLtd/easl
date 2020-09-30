<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$start_date = get_post_meta( get_the_ID(), 'event_start_date', true );
if ( $start_date ) {
	$start_date = new DateTime( "@$start_date" );
}
$nowDate = new DateTime( 'now' );
$days = 0;
if ( false !== $start_date ){
	$interval = $start_date->diff($nowDate);
	if(false !== $interval){
		$days = $interval->days + 1;
	}
}

if ( $days && ( $start_date > $nowDate ) ):
	?>
    <div class="event-sidebar-item easl-small-event-sbitem easl-small-event-sbitem-countdown">
        <div class="easl-small-event-sbitem-inner">
	        <div class="easl-small-event-countdown">
		        <strong><?php echo $days ?></strong>
		        <span><?php echo _n('Day left', 'Days left', $days, 'total-child'); ?></span>
	        </div>
        </div>
    </div>
<?php endif; ?>