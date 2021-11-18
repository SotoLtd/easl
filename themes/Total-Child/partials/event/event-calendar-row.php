<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @var int $row_count
 * @var string $previous_events_month
 * @var string $css_animation
 */
$event_id = get_the_ID();
$event_data = get_post_meta($event_id);

$organisations_list = easl_event_get_organisations();

$event_start_date = isset($event_data['event_start_date'])?$event_data['event_start_date'][0]:'';
$event_end_date = isset($event_data['event_end_date'])?$event_data['event_end_date'][0]:'';
$event_location_venue = isset($event_data['event_location_venue'])?$event_data['event_location_venue'][0]:'';
$event_location_city = isset($event_data['event_location_city'])?$event_data['event_location_city'][0]:'';
$event_location_country = isset($event_data['event_location_country'])?$event_data['event_location_country'][0]:'';
$event_location_display_format = isset($event_data['event_location_display_format'])?$event_data['event_location_display_format'][0]:'';
$event_organisation = isset($event_data['event_organisation'])?$organisations_list[$event_data['event_organisation'][0]]:'';

if( function_exists('get_field')){
	$event_online_programme_url = get_field('event_online_programme_url');
	$event_website_url = get_field('event_website_url');
	$event_notification_url = get_field('event_notification_url');
	$event_why_attend = trim(get_field('event_why_attend'));
	$event_who_should_attend = trim(get_field('event_who_should_attend'));
	$event_topic_covered = trim(get_field('event_topic_covered'));
	$key_dates = get_field('event_key_deadline_row');
	$event_organisers = trim(get_field('event_organisers'));
	$event_highlights = get_field('event_highlights');
}else{
	$event_online_programme_url = get_post_meta(get_the_ID(), 'event_online_programme_url', true);
	$event_website_url = get_post_meta(get_the_ID(), 'event_website_url', true);
	$event_why_attend = trim(get_post_meta(get_the_ID(), 'event_why_attend', true));
	$event_who_should_attend = trim(get_post_meta(get_the_ID(), 'event_who_should_attend', true));
	$event_topic_covered = trim(get_post_meta(get_the_ID(), 'event_topic_covered', true));
	$key_dates = get_post_meta(get_the_ID(), 'event_key_deadline_row', true);
	$event_organisers = trim(get_post_meta(get_the_ID(), 'event_organisers', true));
	$event_highlights = get_post_meta(get_the_ID(), 'event_highlights', true);
}

$now_time = time() - 86399;
$event_time_type = 'upcoming';
if( ($event_start_date < $now_time) && ($event_end_date < $now_time) ){
	$event_time_type = 'past';
}
if( ($event_start_date < $now_time) && ($event_end_date >= $now_time) ){
	$event_time_type = 'current';
}

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
$event_location_display     = easl_get_formatted_event_location( $event_id );

$event_color = easl_get_events_topic_color($event_id);

if(11766 == get_the_ID()){
	$current_events_month = date('Y', $event_start_date);
}else{
    $current_events_month = date('F Y', $event_start_date);
}

$new_month_row = false;
if($previous_events_month !== $current_events_month){
	$new_month_row = true;
	$previous_events_month = $current_events_month;
}

$row_position = 'left';
if($row_count % 2 == 0){
	$row_position = 'right';
}

$event_topics_name = easl_event_topics_name($event_id);

$event_highlights = wp_parse_args($event_highlights, array(
	'section_title' => '',
	'cover_image' => '',
	'pdf_url' => '',
));
?>


	<?php if($new_month_row): ?> 
	<div class="easl-ec-row easl-ec-row-month clr">
		<div class="easl-ec-month-label <?php if($row_count > 1){echo $css_animation;} ?>">
			<span><?php echo $current_events_month; ?></span>
		</div>
	</div>
	<?php endif;?>
	<div class="easl-ec-row easl-ec-row-<?php echo $row_position; ?> easl-ec-row-<?php echo $event_color; ?> clr">
		<article class="easl-ec-event <?php echo $css_animation; ?>">
			<header class="ec-head">
				<?php if($event_topics_name || $event_organisers): ?>
				<p class="ec-meta">
                    <span class="ec-meta-inner">
                        <?php if($event_topics_name): ?>
                        <span class="ec-meta-type">Topic:</span> <span class="ec-meta-value"><?php echo $event_topics_name; ?></span>
                        <?php endif; ?>
                        <?php if($event_topics_name && $event_organisers): ?>
                        <span class="ec-meta-sep"> | </span>
                        <?php endif; ?>
                        <?php if( $event_organisers): ?>
                        <span class="ec-meta-type">Organisers:</span> <span class="ec-meta-value"><?php echo esc_html($event_organisers); ?></span>
                        <?php endif; ?>
                    </span>
				</p>
				<?php endif; ?>
				<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php
                $date_parts_format = easl_get_event_date_format(get_the_ID());
                ?>
				<p class="ec-dates">
                    <?php if('Y' == $date_parts_format): ?>
                        <span class="ecd-year" style="font-size: 20px;line-height: 20px;margin-top: 25px;"><?php echo date('Y', $event_start_date); ?></span>
                    <?php elseif ('mY' == $date_parts_format):?>
                        <span class="ecd-mon" style="margin-top: 13px;"><?php echo $event_date_month; ?></span>
                        <span class="ecd-year" style="font-size: 20px;line-height: 20px;"><?php echo date('Y', $event_start_date); ?></span>
                    <?php else: ?>
                        <span class="ecd-day"><?php echo $event_date_days; ?></span>
                        <span class="ecd-mon"><?php echo $event_date_month; ?></span>
                        <span class="ecd-year" ><?php echo date('Y', $event_start_date); ?></span>
                    <?php endif; ?>
                    <i class="ticon ticon-play" aria-hidden="true"></i></p>
			</header>
			<p class="ec-location">
				<span class="ec-loc-name"><?php echo easl_meeting_type_name($event_id); ?></span>
				<span class="ec-meta-sep"> | </span>
				<span class="ec-country"><?php echo $event_location_display; ?></span>
			</p>
			<p class="ec-excerpt"></p>
			<div class="ec-icons clr">
				<ul class="ec-icons-nav clr">
					<li class="ec-links-more">
						<a class="event-link-item" href="<?php the_permalink(); ?>">
                            <span class="icon-wrapper">
                                <span class="ec-links-icon info"></span>
                            </span>
							<span class="ec-link-text"> More <br/>Information</span>
						</a>
					</li>
					<?php if($event_website_url): ?>
					<li class="ec-links-website">
						<a class="event-link-item" href="<?php echo esc_url( $event_website_url ); ?>" target="_blank">
                            <span class="icon-wrapper">
                                <span class="ec-links-icon laptop"></span>
                            </span>
							<span class="ec-link-text"> Visit <br/>Website</span>
						</a>
					</li>
					<?php endif; ?>
					<?php if($event_time_type == 'past' && $event_highlights['pdf_url']): ?>
                        <li class="ec-links-highlights">
                            <a class="event-link-item" href="<?php echo esc_url($event_highlights['pdf_url']); ?>" target="_blank">
                            <span class="icon-wrapper">
                                <span class="ec-links-icon list"></span>
                            </span>
                                <span class="ec-link-text"> Event <br/>Highlights</span>
                            </a>
                        </li>
					<?php endif; ?>
					<?php if($key_dates): ?>
					<li class="ec-links-deadline">
						<a class="event-link-item" href="">
                            <span class="icon-wrapper">
                                <span class="ec-links-icon clock"></span>
                            </span>
							<span class="ec-link-text"> Key <br/>Deadlines</span>
						</a>
					</li>
					<?php endif; ?>
					<?php if($event_online_programme_url): ?>
					<li class="ec-links-program">
						<a class="event-link-item" href="<?php echo esc_url( $event_online_programme_url );?>" target="_blank">
                            <span class="icon-wrapper">
                                <span class="ec-links-icon list"></span>
                            </span>
							<span class="ec-link-text"> Scientific <br/>Programme</span>
						</a>
					</li>
					<?php endif; ?>
					<?php if($event_notification_url && ('past' != $event_time_type)): ?>
					<li class="ec-links-notify">
						<a class="event-link-item" href="<?php echo esc_url($event_notification_url);?>" target="_blank">
                            <span class="icon-wrapper">
                                <span class="ec-links-icon envelope"></span>
                            </span>
							<span class="ec-link-text"> Get <br/>Notified</span>
						</a>
					</li>
					<?php endif; ?>
					<?php if('past' != $event_time_type && get_field('atc_enable', $event_id)): ?>
                        <?php
                        $atc_alt_title = get_field('atc_alt_title', $event_id);
                        $atc_start_time = get_field('atc_start_time', $event_id);
                        $atc_end_time = get_field('atc_end_time', $event_id);
                        $atc_description = get_field('atc_description', $event_id);
                        
                        if(!$atc_alt_title) {
                            $atc_alt_title = get_the_title($event_id);
                        }
                        $atc_start_date = '';
                        if ( $event_start_date ){
                            $atc_start_date = date( 'm/d/Y', $event_start_date );
                            if($atc_start_time) {
                                $atc_start_date .= ' ' . $atc_start_time;
                            }
                        }
                        $atc_end_date = '';
                        if ( $event_end_date ){
                            $atc_end_date = date( 'm/d/Y', $event_end_date );
                            if($atc_end_time) {
                                $atc_end_date .= ' ' . $atc_end_time;
                            }
                        }
                        
                        ?>
					<li class="ec-links-calendar">
						<div title="Add to Calendar" class="addeventatc">
							<span class="event-link-item">
								<span class="icon-wrapper">
									<span class="ec-links-icon calendar"></span>
								</span>
								<span class="ec-link-text"> Add to <br/>Calendar</span>
							</span>
                            <?php if ( $atc_start_date ): ?>
                                <span class="start"><?php echo $atc_start_date; ?></span>
                            <?php endif; ?>
                            <?php if ( $atc_end_date ): ?>
                                <span class="end"><?php echo $atc_end_date; ?></span>
                            <?php endif; ?>
                            <span class="date_format">MM/DD/YYYY</span>
                            <?php if ( $atc_description ): ?>
                                <span class="description">Description of event</span>
                            <?php endif; ?>
                            <span class="timezone">Europe/Zurich</span>
                            <span class="title"><?php echo $atc_alt_title; ?></span>
                            <?php if($event_location_display): ?>
                                <span class="location"><?php echo $event_location_display; ?></span>
                            <?php endif; ?>
						</div>
					</li>
					<?php endif; ?>
				</ul>
				<?php if($key_dates): ?>
				<div class="ec-links-details ec-links-details-key-deadlines">
					<ul>
                        <?php 
						if(!$key_dates){
							$key_dates = array();
						}
                        $counter = 0;
                        foreach ($key_dates as $date):?>
                            <?php switch($counter):
                                case 0:
                                    $addon_class = 'active';
                                    break;
                                case 1:
                                    $addon_class = 'next-key';
                                    break;
                                default:
                                    $addon_class = '';

                            endswitch;
                            $kd_start_date = !empty($date['event_key_start_date']) ? trim($date['event_key_start_date']): '';
	                        $kd_end_date = ! empty( $date['event_key_end_date'] ) ? trim( $date['event_key_end_date'] ) : '';
	                        
	                        $kd_start_date = DateTime::createFromFormat('d/m/Y', $kd_start_date);
	                        $kd_end_date   = DateTime::createFromFormat( 'd/m/Y', $kd_end_date );
	                        if ( false === $kd_start_date ) {
		                        continue;
	                        }

	                        $date_parts        = array();
	                        $date_parts['d'][] = $kd_start_date->format( 'd' );
	                        $date_parts['m'][] = $kd_start_date->format( 'M' );
	                        $date_parts['y'][] = $kd_start_date->format( 'Y' );
	                        if ( false !== $kd_end_date ) {
		                        $date_parts['d'][] = $kd_end_date->format( 'd' );
		                        $date_parts['m'][] = $kd_end_date->format( 'M' );
		                        $date_parts['y'][] = $kd_end_date->format( 'Y' );
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
                            ?>
                            <li class="app-process-key <?php echo $addon_class;?>">
                                <span class="event-kd-date"><?php echo $formatted_date;?></span>
                                <span class="event-kd-title"><?php echo strip_tags($date['event_key_deadline_description'], '<br>');?></span>
                            </li>
                            <?php $counter++;?>
                        <?php endforeach;?>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</article>
	</div>

