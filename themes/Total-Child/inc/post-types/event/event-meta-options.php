<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$prefix = 'event_';
return array(
	'event-data' => array(
		'title' => esc_html__( 'Event Data', 'total' ),
		'post_type'               => array( 'event' ),
		'settings' => array(
			'location_venue' => array(
				'title' => esc_html__( 'Location - Venue Name', 'total' ),
				'id' => $prefix . 'location_venue',
				'type' => 'text',
				'description' => esc_html__( 'Enter the venue of the event.', 'total' ),
			),
			'location_city' => array(
				'title' => esc_html__( 'Location - City', 'total' ),
				'id' => $prefix . 'location_city',
				'type' => 'text',
				'description' => esc_html__( 'Enter the city of the event.', 'total' ),
			),
			'location_country' => array(
				'title' => esc_html__( 'Location - Country', 'total' ),
				'id' => $prefix . 'location_country',
				'type' => 'select',
				'description' => esc_html__( 'Select the country of the event location.', 'total' ),
				'options' => easl_event_get_countries(),
			),
			'location_display_format' => array(
				'title' => esc_html__( 'Location Display Format', 'total' ),
				'id' => $prefix . 'location_display_format',
				'type' => 'select',
				'description' => esc_html__( 'Select the format of location display.', 'total' ),
				'options' => array(
					'venue|city,contury' => __('Venue | City, Country', 'total-child'),
					'venue,Country' => __('Venue, Country', 'total-child'),
					'venue' => __('Venue Only', 'total-child'),
					'city,contury' => __('City, Country', 'total-child'),
				),
				'value' => 'venue|city,contury'
			),
            'programme' => array(
                'title'=> esc_html__('Programme', 'total'),
                'id' => $prefix . 'programme',
                'type' => 'select',
                'description' => esc_html__('Select the source of programme.', 'total'),
                'options' => easl_event_get_programme(),
            ),
			'start_date' => array(
				'title' => esc_html__( 'Start Date', 'total' ),
				'id' => $prefix . 'start_date',
				'type' => 'date',
				'description' => esc_html__( 'Enter the start date of the event.', 'total' ),

			),
			'end_date' => array(
				'title' => esc_html__( 'End Date', 'total' ),
				'id' => $prefix . 'end_date',
				'type' => 'date',
				'description' => esc_html__( 'Enter the end date of the event.', 'total' ),

			),
            'date_display_format' => array(
                'title' => esc_html__( 'Date Display Format', 'total' ),
                'id' => $prefix . 'date_display_format',
                'type' => 'select',
                'description' => esc_html__( 'Select the format of date display.', 'total' ),
                'options' => array(
                    '' => __('Day, Month, Year', 'total-child'),
                    'mY' => __('Month, Year', 'total-child'),
                    'Y' => __('Year', 'total-child'),
                ),
                'value' => 'venue|city,contury'
            ),
            'event_website_url' => array(
                'title' => esc_html__( 'Event Website URL', 'total' ),
                'id' => $prefix . 'website_url',
                'type' => 'text',
                'description' => esc_html__( 'Enter the Event Website URL.', 'total' ),
            ),
            'online_programme_url' => array(
                'title' => esc_html__( 'Online Programme URL', 'total' ),
                'id' => $prefix . 'online_programme_url',
                'type' => 'text',
                'description' => esc_html__( 'Enter the Online Programme URL.', 'total' ),
            ),
            'notification_url' => array(
                'title' => esc_html__( 'Notification URL', 'total' ),
                'id' => $prefix . 'notification_url',
                'type' => 'text',
                'description' => esc_html__( 'Enter the Notification URL.', 'total' ),
            ),
//            'submit_abstract' => array(
//                'title' => esc_html__( 'Submit Abstract', 'total' ),
//                'id' => $prefix . 'submit_abstract_url',
//                'type' => 'text',
//                'description' => esc_html__( 'Enter the Abstract Submission URL.', 'total' ),
//            ),
//            'register' => array(
//                'title' => esc_html__( 'Register', 'total' ),
//                'id' => $prefix . 'register_url',
//                'type' => 'text',
//                'description' => esc_html__( 'Enter the Registration URL.', 'total' ),
//            ),
			'application' => array(
				'title' => esc_html__( 'Application URL', 'total' ),
				'id' => $prefix . 'application_url',
				'type' => 'text',
				'description' => esc_html__( 'Enter the Application URL.', 'total' ),
			),
            'sponsorship_Link_url' => array(
                'title' => esc_html__( 'Sponsorship Link URL', 'total' ),
                'id' => $prefix . 'sponsorship_url',
                'type' => 'text',
                'description' => esc_html__( 'Enter Sponsorship Link URL.', 'total' ),

            ),
            'bursary_Link_url' => array(
                'title' => esc_html__( 'Bursary Link URL', 'total' ),
                'id' => $prefix . 'bursary__url',
                'type' => 'text',
                'description' => esc_html__( 'Enter Bursary Link URL.', 'total' ),

            ),
            'press_Link_url' => array(
                'title' => esc_html__( 'Press Link URL', 'total' ),
                'id' => $prefix . 'press_url',
                'type' => 'text',
                'description' => esc_html__( 'Enter Press Link URL.', 'total' ),

            ),

		)
	),
);