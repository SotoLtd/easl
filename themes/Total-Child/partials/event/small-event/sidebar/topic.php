<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$topic_obj  = easl_get_event_topic_obj( get_the_ID() );
if($topic_obj):
	$topic_events_link = add_query_arg('topics[]', $topic_obj->term_id, get_the_permalink(119));
?>
<div class="easl-small-event-sbitem easl-small-event-sbitem-topic">
	<a href="<?php echo $topic_events_link; ?>"><?php echo $topic_obj->name; ?></a>
</div>
<?php endif; ?>
