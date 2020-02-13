<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$event_data = get_post_meta( get_the_ID() );
$event_start_date = '';
$event_end_date = '';
if ( ! empty( $event_data['event_start_date'][0] ) ) {
	$event_start_date = $event_data['event_start_date'][0];
}
if ( ! empty( $event_data['event_end_date'][0] ) ) {
	$event_end_date = $event_data['event_end_date'][0];
}
$event_color = easl_get_events_topic_color(get_the_ID());
$event_meeting_type_name = easl_meeting_type_name(get_the_ID());
$event_location_display = easl_get_event_location(get_the_ID(), 'city,contury');

$event_date_days = date('d', $event_start_date);
if($event_end_date > $event_start_date){
	$event_date_days .= '-' . date('d', $event_end_date);
}
$event_date_month = '';
$event_start_month = '';
$event_end_month = '';
if($event_start_date){
	$event_start_month = date('M', $event_start_date);
}
if($event_end_date){
	$event_end_month = date('M', $event_end_date);
}
if($event_start_month){
	$event_date_month = $event_start_month;
}
if($event_end_month && ($event_start_month != $event_end_month)){
	$event_date_month .= '/' . $event_end_month;
}
?>
<li class="easl-events-li easl-event-li-<?php echo $event_color; ?>">
	<h3><a title="" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<a class="events-li-date" href="<?php the_permalink(); ?>">
		<span class="eld-day"><?php echo $event_date_days; ?></span>
		<span class="eld-mon"><?php echo $event_date_month; ?></span>
		<span class="eld-year"><?php echo date('Y', $event_start_date); ?></span>
		<i class="ticon ticon-play" aria-hidden="true"></i>
	</a>
	<?php if($event_meeting_type_name || $event_location_display): ?>
		<p class="el-location">
			<?php if($event_meeting_type_name): ?>
				<span class="ell-name"><?php echo $event_meeting_type_name; ?></span>
			<?php endif; ?>
			<?php if($event_meeting_type_name && $event_location_display): ?>
				<span class="ell-bar">|</span>
			<?php endif; ?>
			<?php if($event_location_display): ?>
				<span class="ell-country"><?php echo $event_location_display; ?></span>
			<?php endif; ?>
		</p>
	<?php endif; ?>
</li>