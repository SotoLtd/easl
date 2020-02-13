<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if( class_exists('WPBakeryShortCode') ){
    class EASL_Vc_Highlights extends WPBakeryShortCode {
        public function load_highlights(){
            $topic = !empty($_REQUEST['topic']) ? $_REQUEST['topic'] : '';
            if(empty($topic)){
	            wp_send_json(array(
		            'status' => 'NOTOK',
                    'msg' => __('Please select a topic filter', 'total-child')
	            ));
            }
			$now_time = time();
			$events_args = array(
				'post_type' => EASL_Event_Config::get_event_slug(),
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'order' => 'ASC',
				'orderby' => 'meta_value_num',
				'meta_key' => 'event_start_date',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'event_organisation',
						'value' => 1,
						'compare' => '=',
						'type' => 'NUMERIC',
					),
					array(
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
					),
				)
			);
			$publication_args = array(
				'post_type' => Publication_Config::get_publication_slug(),
				'post_status' => 'publish',
				'posts_per_page' => 1,
			);
			$slide_decks_args = array(
				'post_type' => Slide_Decks_Config::get_slug(),
				'post_status' => 'publish',
				'posts_per_page' => 2,
			);
	        if( $topic  && ('all' != $topic) ){
		        $events_args['tax_query'] = array(
			        array(
				        'taxonomy' => EASL_Event_Config::get_topic_slug(),
				        'field' => 'slug',
				        'terms' => array($topic),
			        )
		        );
		        $publication_args['tax_query'] = array(
			        array(
				        'taxonomy' => Publication_Config::get_topic_slug(),
				        'field' => 'slug',
				        'terms' => array($topic),
			        )
		        );
		        $slide_decks_args['tax_query'] = array(
			        array(
				        'taxonomy' => Slide_Decks_Config::get_topic_slug(),
				        'field' => 'slug',
				        'terms' => array($topic),
			        )
		        );
            }
            $latest_event = new WP_Query($events_args);
            if('general-hepatology' != $topic && !$latest_event->have_posts()){
                $events_args['tax_query'] = array(
                    array(
                        'taxonomy' => EASL_Event_Config::get_topic_slug(),
                        'field' => 'slug',
                        'terms' => array('general-hepatology'),
                    )
                );
                $latest_event = new WP_Query($events_args);
            }
            $latest_publication = new WP_Query( $publication_args);
            $latest_slide_desks = new WP_Query($slide_decks_args);
            $items = array(
                'events' => '',
                'publications' => '',
                'slide_decks' => '',
            );

	        ob_start();
            while($latest_event->have_posts()){
                $latest_event->the_post();
	            get_template_part('partials/event/event-loop');
            }
	        $items['events'] = ob_get_clean();
	        wp_reset_query();
	        ob_start();
	        while($latest_publication->have_posts()){
		        $latest_publication->the_post();
		        get_template_part('partials/highlights/publications');
	        }
	        $items['publications'] = ob_get_clean();
	        wp_reset_query();
	        ob_start();
	        while($latest_slide_desks->have_posts()){
		        $latest_slide_desks->the_post();
		        get_template_part('partials/highlights/slide-decks');
	        }
	        $items['slide_decks'] = ob_get_clean();
	        wp_reset_query();

	        wp_send_json(array(
		        'status' => 'OK',
		        'items' => $items,
	        ));
        }
    }
}

add_action('wp_ajax_easl_get_highlights', array('EASL_Vc_Highlights', 'load_highlights'));
add_action('wp_ajax_nopriv_easl_get_highlights', array('EASL_Vc_Highlights', 'load_highlights'));
