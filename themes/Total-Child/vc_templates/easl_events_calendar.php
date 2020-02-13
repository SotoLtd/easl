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
 * Shortcode class EASL_VC_Events_Calendar
 * @var $this EASL_VC_Events_Calendar
 */
$event_number = $event_type = $title = $element_width = $view_all_link = $view_all_url = $view_all_text = $el_class = $el_id = $css_animation = '';
$enable_related_links = $relink_title = $related_links = $all_topic_events = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );


$topics_req = !empty($_REQUEST['topics']) ? $this->string_to_array($_REQUEST['topics']): false;
$meeting_type_req = !empty($_REQUEST['type']) ? absint($_REQUEST['type']): false;
if(!$topics_req) {
	$topics_req = array();
}

$css_animation = $this->getCSSAnimation($css_animation);

if(!$view_all_text){
	$view_all_text = 'View all Events';
}

if($title && $view_all_link){
	$title .= '<a class="easl-events-all-link" href="'. esc_url($view_all_url) .'">' . $view_all_text . '</a>';
}

$related_links_data = array();
if($enable_related_links){
	$related_links_data = $this->get_related_links_data($related_links);
}
$related_links_html = '';
if($enable_related_links){
	$related_links_html .= '<div class="easl-ec-related-links-wrap">' . "\n\t";
	$related_links_html .= '<h4 class="easl-ec-related-links-title">'. $relink_title .'</h4>' . "\n\t";
	$related_links_html .= '<ul class="easl-ec-related-links">' . "\n\t\t";
	foreach($related_links_data as $rel_link) {
		$link_attributes = array();
		$link_attributes[] = 'href="' . trim( $rel_link['url'] ) . '"';
		if ( ! empty( $rel_link['target'] ) ) {
			$link_attributes[] = 'target="' . esc_attr( trim( $rel_link['target'] ) ) . '"';
		}
		if ( ! empty( $rel_link['rel'] ) ) {
			$link_attributes[] = 'rel="' . esc_attr( trim( $rel_link['rel'] ) ) . '"';
		}
		$link_attributes = implode( ' ', $link_attributes );
		$related_links_html .= '<li><a '. $link_attributes .' href="'. esc_url($rel_link['url']) .'">'. $rel_link['title'] .'<span class="ticon ticon-angle-right"></span></span></a></li>' . "\n\t";
	}
	$related_links_html .= '</ul>' . "\n";
	$related_links_html .= '</div>';
}


$topics_list = '
	<li>
		<label class="easl-custom-checkbox easl-cb-all csic-light-blue">
			<input type="checkbox" name="ec_filter_topics[]" value="" checked="checked"/> <span>All topics</span>
		</label>
	</li>
	';

$topics = get_terms( array(
	'taxonomy' => EASL_Event_Config::get_topic_slug(),
	'hide_empty' => false,
	'orderby' => 'name',
	'order' => 'ASC',
	'fields' => 'id=>name',
	'exclude' => array(787),
) );

if( !is_array($topics)){
	$topics = array();
}

$topic_events_map = EASL_VC_Events_Calendar::events_topic_map();

foreach($topics as $topic_id => $topic_name){
	$topic_color = get_term_meta($topic_id, 'easl_tax_color', true);
	if(!$topic_color){
		$topic_color = 'blue';
	}
	$topic_events = isset($topic_events_map[$topic_id]) ? $topic_events_map[$topic_id] : array();
	$topics_list .= '
		<li>
			<label class="easl-custom-checkbox csic-'. $topic_color .'">
				<input type="checkbox" name="ec_filter_topics[]" value="'. $topic_id .'" data-events="'. esc_attr( json_encode($topic_events)) .'" '. checked(in_array($topic_id, $topics_req), true, false) .' /> <span style="">'. esc_html($topic_name) .'</span>
			</label>
		</li>
		';
}
$meeting_type_list = '
	<option value="">Meeting Type</option>
	';
$meeting_types = get_terms( array(
	'taxonomy' => EASL_Event_Config::get_meeting_type_slug(),
	'hide_empty' => false,
	'orderby' => 'term_id',
	'order' => 'ASC',
	'fields' => 'id=>name',
	'parent' => '0',
) );

if( !is_array($meeting_types)){
	$meeting_types = array();
}
$meeting_types_events_map = EASL_VC_Events_Calendar::events_meeting_type_map();
foreach($meeting_types as $meeting_typ_id => $meeting_type_name){
	$meeting_type_events = isset($meeting_types_events_map[$meeting_typ_id]) ? $meeting_types_events_map[$meeting_typ_id] : array();
	$meeting_type_list .= '
		<option value="'. $meeting_typ_id .'" data-events="'. esc_attr( json_encode($meeting_type_events)) .'" '. selected($meeting_typ_id, $meeting_type_req, false) .'>'. esc_html($meeting_type_name) .'</option>
		';
}

$location_list = '
	<option value="">Location</option>
	';
$countries = easl_event_db_countries();

$countries_events_map = EASL_VC_Events_Calendar::events_countries_map();
foreach($countries as $country_code => $country_name){
	$country_events = isset($countries_events_map[$country_code]) ? $countries_events_map[$country_code] : array();
	$location_list .= '
		<option value="'. $country_code .'" data-events="'. esc_attr( json_encode($country_events)) .'">'. esc_html($country_name) .'</option>
		';
}

$organizer_events_map = EASL_VC_Events_Calendar::events_organiser_map();
$past_future_events_map = EASL_VC_Events_Calendar::events_past_future_event_map();

$top_filter = '
	<div class="easl-ec-filter-container">
		<div class="easl-ec-filter easl-row">
			<div class="easl-col easl-col-4 ec-filter-showme">
				<div class="easl-col-inner">
					<h4>Show me:</h4>
					<ul class="ec-filter-topics">
						'. $topics_list .'
					</ul>
					<div class="easl-filter-reset-mobwrap ec-hide-desktop">
						<button class="easl-ecf-reset"><i class="ticon ticon-times-circle"></i> Clear Filters</button>
					</div>
				</div>   
			</div>
			<div class="easl-col easl-col-3-4">
				<div class="easl-col-inner">
					<div class="ec-filter-search">
						<input type="text" name="ecf_search" value="" placeholder="Search"/>
						<span class="ecs-icon"><i class="ticon ticon-search" aria-hidden="true"></i></span>
					</div>
					<div class="easl-row">
						<div class="easl-col easl-col-2-3">
							<div class="easl-col-inner">
							    <h2 class="ec-hide-mob" style="margin-bottom: 10px;">Filter by:</h2>
							    <div class="ec-hide-desktop" style="margin-bottom: 5px;">
							    	<button class="ec-mob-showhide-filter">
							    		<span class="ec-mob-show-filter">Show Filters</span>
							    		<span class="ec-mob-hide-filter">Hide Filters</span>
							    	</button>
							    </div>
								<div class="ec-filter-fields">
								    <div class="ec-filter-field-wrap">
										<div class="ecf-events-types" style="margin-bottom: 15px">
											<label class="easl-custom-radio"><input type="radio" name="organizer" data-events="'. esc_attr( json_encode($organizer_events_map[1])) .'" value="1" checked="checked"/> <span>EASL organised</span></label>
											<label class="easl-custom-radio"><input type="radio" name="organizer" data-events="'. esc_attr( json_encode($organizer_events_map[2])) .'" value="2"/> <span>Other events</span></label>
										</div>
									
										<div class="easl-custom-select easl-custom-select-filter-type">
											<span class="ec-cs-label">Meeting type</span>
											<select name="ec_meeting_type">
												'. $meeting_type_list .'
											</select>
										</div>
									</div>
									<div class="ec-filter-field-wrap ec-filter-field-location">
										<div class="ecf-events-types" style="margin-bottom: 15px">
											<label class="easl-custom-radio"><input type="radio" name="ec_filter_type" data-events="'. esc_attr( json_encode($past_future_events_map['future'])) .'"  value="future" checked="checked"/> <span>Future events</span></label>
											<label class="easl-custom-radio"><input type="radio" name="ec_filter_type" data-events="'. esc_attr( json_encode($past_future_events_map['past'])) .'" value="past"/> <span>Past events</span></label>
										</div>
									
										<div class="easl-custom-select easl-custom-select-filter-location">
											<span class="ec-cs-label">Location</span>
											<select name="ec_location">
												'. $location_list .'
											</select>
										</div>
									</div>
									<div class="ec-filter-field-wrap ec-hide-mob">
										
									</div>
									<div class="ec-filter-field-wrap ec-hide-mob" style="margin-bottom:0;margin-top: 34px;">
										<div class="easl-filter-reset-wrap">
											<button class="easl-ecf-reset"><i class="ticon ticon-times-circle"></i> Clear filters</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="easl-col easl-col-3 ec-related-links-col ec-hide-mob">
							<div class="easl-col-inner">
								' . $related_links_html . '
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="easl-ec-related-links-mob ec-hide-desktop">' . $related_links_html. '</div>
	';

$now_time = time();

// Quick debug purpose or filtering $event_number
if(isset($_GET['event_type'])){
	$event_type = trim($_GET['event_type']);
}
if( !in_array( $event_type, array('all', 'past', 'future', 'current') )){
	$event_type = 'future';
}
if(isset($_GET['event_number'])){
	$event_number = trim($_GET['event_number']);
}
if(!$event_number){
	$event_number = 5;
}


$event_args = array(
	'post_type' => EASL_Event_Config::get_event_slug(),
	'post_status' => 'publish',
	'posts_per_page' => $event_number,
	'order' => 'ASC',
	'orderby' => 'meta_value_num',
	'meta_key' => 'event_start_date',
);
$meta_query_date = array();
if('future' == $event_type){
	$event_args['order'] = 'ASC';
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
	$event_args['order'] = 'DESC';
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
}elseif('current' == $event_type){
	$event_args['order'] = 'ASC';
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
            'compare' => '>=',
            'type' => 'NUMERIC',
		),
	);
}


$organizer = !empty($_REQUEST['organizer']) ? absint($_REQUEST['organizer']) : 1;
$meta_query[] = array(
	'relation' => 'AND',
	array(
		'key' => 'event_organisation',
		'value' => $organizer,
		'compare' => '=',
		'type' => 'NUMERIC',
	)
);
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
// Topic
$all_topic_events = absint($all_topic_events);
if( is_array($topics_req) && count($topics_req) > 0){
	if($all_topic_events) {
		$topics_req[] = $all_topic_events;
	}
	$tax_query[] = array(
		'taxonomy' => EASL_Event_Config::get_topic_slug(),
		'field' => 'term_id',
		'terms' => $topics_req,
	);
}
// Meeting Type
if( $meeting_type_req){
	$tax_query[] = array(
		'taxonomy' => EASL_Event_Config::get_meeting_type_slug(),
		'field' => 'term_id',
		'terms' => array($meeting_type_req),
	);
}
// Check if there is any topic/meeting type
if(count($tax_query) > 0){
	$tax_query['relation'] = 'AND';
	$event_args['tax_query'] = $tax_query;
}

$event_query = new WP_Query($event_args);

$paged = 1;
$event_wrapper_data = array();
if($event_query->max_num_pages <= $paged){
	$event_wrapper_data[] = 'data-lastpage="true"';
}else{
	$event_wrapper_data[] = 'data-lastpage="false"';
}

if($css_animation){
	$event_wrapper_data[] = 'data-cssanimation="'. esc_attr($css_animation) .'"';
}

if($all_topic_events) {
	$event_wrapper_data[] = ' data-alltopic="'. $all_topic_events .'"';
	$alltopiceventsmap= isset($topic_events_map[$all_topic_events]) ? $topic_events_map[$all_topic_events] : array();
	$event_wrapper_data[] = ' data-alltopicevents="'. esc_attr( json_encode($alltopiceventsmap)) .'"';
}

$rows = '';
if($event_query->have_posts()){
	$previous_events_month = '';
	$row_count = 0;
	
	while ($event_query->have_posts()){
		$event_query->the_post();
		$row_count++;
		ob_start();
		include get_stylesheet_directory() . '/partials/event/event-calendar-row.php';
		$rows .= ob_get_clean();
	}
	wp_reset_postdata();
}

$class_to_filter = 'wpb_easl_events_calendar wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );



$html = '<div class="easl-events-calendar-wrap" '. implode( ' ', $event_wrapper_data ) .'>
			'. $top_filter .'
			<div class="easl-events-calendar-inner">
				'. $rows .'
			</div>
			<div class="easl-events-calendar-bottom">
				<div class="easl-ec-row easl-ec-row-ball"><span></span></div>
				<div class="easl-ec-load-more">
					<div class="easl-ec-load-icon">
						<img class="easl-loading-icon" src="' . get_stylesheet_directory_uri() . '/images/easl-loader.gif"/>
					</div>
					<p class="easl-ec-load-text">Loading More</p>
				</div>
			</div>
		</div>';

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
wp_enqueue_script(
	'appear',
	vcex_asset_url( 'js/lib/jquery.appear.min.js' ),
	array( 'jquery' ),
	'1.0',
	true
);
$output = '
	<div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( trim( $css_class ) ) . '">
		' . wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_easl_events_calendar_heading' ) ) . '
			' . $html . '
	</div>
';
$output .= $this->output_inline_script();
echo $output;