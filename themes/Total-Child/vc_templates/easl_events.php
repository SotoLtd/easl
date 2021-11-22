<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class
 * @var $this EASL_VC_Events
 */
$title = $subtitle = $el_class = $el_id = $css_animation = '';

$numberposts = '';
$topics = '';
$meeting_types = '';
$order = '';
$event_type = '';
$organizer = '';

$view_all_link = '';
$view_all_url = '';
$view_all_text = '';


$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = 'wpb_easl_events wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

if(!$view_all_text){
	$view_all_text = 'View all Events';
}

if($title && $view_all_link){
	$title .= '<a class="easl-events-all-link" href="'. esc_url($view_all_url) .'">' . $view_all_text . '</a>';
}

$widget_subtitle = '';
if ( ! empty( $subtitle ) ) {
	$widget_subtitle .= '<p class="wpb_easl_events_subtitle">' . $subtitle . '</p>';
}

$numberposts = intval($numberposts);

$numberposts = !empty($numberposts) ? $numberposts : -1;

if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {
	$order = 'ASC';
}

$now_time = time();

$event_args = array(
	'post_type' => EASL_Event_Config::get_event_slug(),
	'post_status' => 'publish',
	'posts_per_page' => $numberposts,
	'order' => $order,
	'orderby' => 'meta_value_num',
	'meta_key' => 'event_start_date',
);

$meta_query = array();
$meta_query_date = array();
if('upcoming' == $event_type){
	$meta_query_date[] = array(
		'relation' => 'OR',
		array(
			'key' => 'event_start_date',
			'value' => $now_time - 86399,
			'compare' => '>=',
			'type' => 'NUMERIC',
		),
		array(
			'key' => 'event_end_date',
			'value' => $now_time - 86399,
			'compare' => '>=',
			'type' => 'NUMERIC',
		),
	);
}elseif('past' == $event_type){
	$meta_query_date[] = array(
		'relation' => 'AND',
		array(
			'key' => 'event_start_date',
			'value' => $now_time - 86399,
			'compare' => '<',
			'type' => 'NUMERIC',
		),
		array(
			'key' => 'event_end_date',
			'value' => $now_time - 86399,
			'compare' => '<',
			'type' => 'NUMERIC',
		),
	);
}
switch ($organizer){
    case 'easl':
	    $organizer = 1;
	    break;
    case 'other':
	    $organizer = 2;
	    break;
    case 'easl_endorsed':
	    $organizer = 3;
	    break;
}
if($organizer){
    $meta_query[] = array(
        'key' => 'event_organisation',
        'value' => $organizer,
        'compare' => '=',
        'type' => 'NUMERIC',
    );
}
if(count($meta_query_date) > 0){
	$meta_query[] = $meta_query_date;
}

// Check if there is any meta queyr
if(count($meta_query) > 0){
	$meta_query['relation'] = 'AND';
	$event_args['meta_query'] = $meta_query;
}

// Taxonomy query args
$tax_query = array();
$topics = $this->string_to_array($topics);
$meeting_types = $this->string_to_array($meeting_types);
// Topic
if( is_array($topics) && count($topics) > 0){
	$tax_query[] = array(
		'taxonomy' => EASL_Event_Config::get_topic_slug(),
		'field' => 'term_id',
		'terms' => $topics,
	);
}
// Meeting Type
if( $meeting_types){
	$tax_query[] = array(
		'taxonomy' => EASL_Event_Config::get_meeting_type_slug(),
		'field' => 'term_id',
		'terms' => $meeting_types,
	);
}
// Check if there is any topic/meeting type
if(count($tax_query) > 0){
	$tax_query['relation'] = 'AND';
	$event_args['tax_query'] = $tax_query;
}

$event_query = new WP_Query($event_args);
if($event_query->have_posts()){
	$row_count = 0;
	?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?> class="<?php echo esc_attr( trim( $css_class ) ); ?>">
	<?php 
	echo wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_easl_events_heading' ) ); 
	echo $widget_subtitle;
	?>
	<div class="easl-events-list-wrap">
		<ul>
	<?php
	while ($event_query->have_posts()){
		$event_query->the_post();
		get_template_part('partials/event/event-loop');
	}
	wp_reset_query();
	?>	
		</ul>
	</div>
</div>
<?php
}