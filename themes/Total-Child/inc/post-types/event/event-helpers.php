<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function easl_event_get_organisations(){
	return array(
		1 => 'EASL',
		2 => 'Other',
	);
}
function easl_event_get_programme(){
	return array(
		1 => 'URL',
		2 => 'PDF',
	);
}
//function easl_event_get_key_dates(){
//    return array(
//        1 => 'Abstract submission',
//        2 => 'Early Registration',
//        3 => 'Registration',
//        4 => 'Extended deadline',
//    );
//}

function easl_taxonomoy_colors(){
	return array(
		'' => __('Default', 'total'),
		'blue' => __('Blue', 'total'),
		'light-blue' => __('Light Blue', 'total'),
		'red' => __('Red', 'total'),
		'orrange' => __('Orange', 'total'),
		'teal' => __('Teal', 'total'),
		'gray' => __('Gray', 'total'),
		'yellow' => __('Yellow', 'total'),
	);
}

function easl_get_events_topic_color($id = null){
	if(!$id){
		$id = get_the_ID();
	}
	$args  = array(
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$topics = wp_get_post_terms($id, EASL_Event_Config::get_topic_slug(), $args);
if( !$topics || is_wp_error( $topics )){
		return 'blue';
	}
	if(count($topics) > 1) {
		return 'blue';
	}
	$topic_color = get_term_meta($topics[0]->term_id, 'easl_tax_color', true);
	if(!$topic_color) {
		return 'blue';
	}
	return $topic_color;
}
function easl_get_slide_decks_topic_color($id = null){
	if(!$id){
		$id = get_the_ID();
	}
	$args  = array(
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$topics = wp_get_post_terms($id, Slide_Decks_Config::get_topic_slug(), $args);
	if( !$topics || is_wp_error( $topics )){
		return 'blue';
	}
	if(count($topics) > 1) {
		return 'blue';
	}
	$topic_color = get_term_meta($topics[0]->term_id, 'easl_tax_color', true);
	if(!$topic_color) {
		return 'blue';
	}
	return $topic_color;
}

function easl_publication_topic_id($id = null){
	if(!$id){
		$id = get_the_ID();
	}
	$args  = array(
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$topics = wp_get_post_terms($id, Publication_Config::get_topic_slug(), $args);
	if( !$topics || is_wp_error( $topics )){
		return '';
	}
	return $topics[0]->term_id;
}
function easl_get_publication_topic_color($id = null){
	if(!$id){
		$id = get_the_ID();
	}
	$args  = array(
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$topics = wp_get_post_terms($id, Publication_Config::get_topic_slug(), $args);
	if( !$topics || is_wp_error( $topics )){
		return 'blue';
	}
	if(count($topics) > 1) {
		return 'blue';
	}
	$topic_color = get_term_meta($topics[0]->term_id, 'easl_tax_color', true);
	if(!$topic_color) {
		return 'blue';
	}
	return $topic_color;
}

function easl_get_slide_deck_topic_color($id = null){
	if(!$id){
		$id = get_the_ID();
	}
	$args  = array(
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$topics = wp_get_post_terms($id, Slide_Decks_Config::get_topic_slug(), $args);
	if( !$topics || is_wp_error( $topics )){
		return 'blue';
	}
	if(count($topics) > 1) {
		return 'blue';
	}
	$topic_color = get_term_meta($topics[0]->term_id, 'easl_tax_color', true);
	if(!$topic_color) {
		return 'blue';
	}
	return $topic_color;
}

function easl_event_topics_name($id = null, $first = true, $seperator = ', '){
	if(!$id){
		$id = get_the_ID();
	}
	$args  = array(
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$topics = wp_get_post_terms($id, EASL_Event_Config::get_topic_slug(), $args);
	if( !$topics || is_wp_error( $topics )){
		return '';
	}
	if($first){
		return $topics[0]->name;
	}
	$topic_names = array();
	foreach ($topics as $topic){
		$topic_names[] = $topic->name;
	}
	return implode( $seperator, $topic_names );
}
function easl_publications_topics_name($id = null, $first = true, $seperator = ', '){
	if(!$id){
		$id = get_the_ID();
	}
	$args  = array(
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$topics = wp_get_post_terms($id, Publication_Config::get_topic_slug(), $args);
	if( !$topics || is_wp_error( $topics )){
		return '';
	}
	if($first){
		return $topics[0]->name;
	}
	$topic_names = array();
	foreach ($topics as $topic){
		$topic_names[] = $topic->name;
	}
	return implode( $seperator, $topic_names );
}
function easl_meeting_type_id($id = null){
	if(!$id){
		$id = get_the_ID();
	}
	$types = wp_get_post_terms($id, EASL_Event_Config::get_meeting_type_slug());
	if( !$types || is_wp_error( $types )){
		return '';
	}
	return $types[0]->term_id;
}
function easl_meeting_type_name($id = null){
	if(!$id){
		$id = get_the_ID();
	}
	$types = wp_get_post_terms($id, EASL_Event_Config::get_meeting_type_slug());
	if( !$types || is_wp_error( $types )){
		return '';
	}
	return $types[0]->name;
}

function easl_get_event_location($id = null, $event_location_display_format = ''){
	if(!$id){
		$id = get_the_ID();
	}
	$event_location = array();
	$event_location_venue = get_post_meta(get_the_ID(), 'event_location_venue', true);
	$event_location_city = get_post_meta(get_the_ID(), 'event_location_city', true);
	$event_location_country = get_post_meta(get_the_ID(), 'event_location_country', true);
	if(!$event_location_display_format){
		$event_location_display_format = get_post_meta(get_the_ID(), 'event_location_display_format', true);
	}
	if(!in_array( $event_location_display_format, array('venue|city,contury', 'venue,Country', 'venue', 'city,contury' ))) {
		$event_location_display_format = 'venue|city,contury';
	}

	$event_location_display = array();


	if('venue|city,contury' == $event_location_display_format){
		if($event_location_venue){
			$event_location_display[] = $event_location_venue;
		}
		if($event_location_city){
			$event_location[] = $event_location_city;
		}
		if($event_location_country){
			$event_location[] = easl_event_map_country_key($event_location_country );
		}
		if(count($event_location > 0)){
			$event_location_display[] = implode(', ', $event_location);
		}
		$event_location_display = implode( ' | ', $event_location_display );
	}elseif('venue,Country' == $event_location_display_format){
		if($event_location_venue){
			$event_location_display[] = $event_location_venue;
		}
		if($event_location_country){
			$event_location_display[] = easl_event_map_country_key($event_location_country );
		}
		$event_location_display = implode( ', ', $event_location_display );
	}elseif('venue' == $event_location_display_format){
		$event_location_display = $event_location_venue;
	}elseif('city,contury' == $event_location_display_format){
		if($event_location_city){
			$event_location_display[] = $event_location_city;
		}
		if($event_location_country){
			$event_location_display[] = easl_event_map_country_key($event_location_country );
		}
		$event_location_display = implode( ', ', $event_location_display );
	}else{
		$event_location_display = '';
	}
	return $event_location_display;
}

function easl_event_db_countries(){
	global $wpdb;
	$meta_table = _get_meta_table( 'post' );
	if ( ! $meta_table ) {
		return false;
	}
	$countries = $wpdb->get_col( "SELECT meta_value FROM $meta_table WHERE meta_key='event_location_country' ORDER BY meta_value ASC" );
	if(!$countries){
		return array();
	}
	$full_country_list = easl_event_get_countries();
	$key_countries = array();
	foreach($countries as $country_code){
		if( array_key_exists( $country_code, $full_country_list )){
			$key_countries[$country_code] = $full_country_list[$country_code];
		}
	}
	asort($key_countries);
	return $key_countries;
}

function easl_event_map_country_key($country_code){
	if(!$country_code){
		return '';
	}
	$full_country_list = easl_event_get_countries();
	if( array_key_exists( $country_code, $full_country_list )){
		return $full_country_list[$country_code];
	}
}

/**
 * Get a list of country
 * @return array
 */
function easl_event_get_countries(){
	return array(
		''	 => __('Select a country', 'total'),
		'AF' => __( 'Afghanistan', 'total' ),
		'AX' => __( '&#197;land Islands', 'total' ),
		'AL' => __( 'Albania', 'total' ),
		'DZ' => __( 'Algeria', 'total' ),
		'AS' => __( 'American Samoa', 'total' ),
		'AD' => __( 'Andorra', 'total' ),
		'AO' => __( 'Angola', 'total' ),
		'AI' => __( 'Anguilla', 'total' ),
		'AQ' => __( 'Antarctica', 'total' ),
		'AG' => __( 'Antigua and Barbuda', 'total' ),
		'AR' => __( 'Argentina', 'total' ),
		'AM' => __( 'Armenia', 'total' ),
		'AW' => __( 'Aruba', 'total' ),
		'AU' => __( 'Australia', 'total' ),
		'AT' => __( 'Austria', 'total' ),
		'AZ' => __( 'Azerbaijan', 'total' ),
		'BS' => __( 'Bahamas', 'total' ),
		'BH' => __( 'Bahrain', 'total' ),
		'BD' => __( 'Bangladesh', 'total' ),
		'BB' => __( 'Barbados', 'total' ),
		'BY' => __( 'Belarus', 'total' ),
		'BE' => __( 'Belgium', 'total' ),
		'PW' => __( 'Belau', 'total' ),
		'BZ' => __( 'Belize', 'total' ),
		'BJ' => __( 'Benin', 'total' ),
		'BM' => __( 'Bermuda', 'total' ),
		'BT' => __( 'Bhutan', 'total' ),
		'BO' => __( 'Bolivia', 'total' ),
		'BQ' => __( 'Bonaire, Saint Eustatius and Saba', 'total' ),
		'BA' => __( 'Bosnia and Herzegovina', 'total' ),
		'BW' => __( 'Botswana', 'total' ),
		'BV' => __( 'Bouvet Island', 'total' ),
		'BR' => __( 'Brazil', 'total' ),
		'IO' => __( 'British Indian Ocean Territory', 'total' ),
		'VG' => __( 'British Virgin Islands', 'total' ),
		'BN' => __( 'Brunei', 'total' ),
		'BG' => __( 'Bulgaria', 'total' ),
		'BF' => __( 'Burkina Faso', 'total' ),
		'BI' => __( 'Burundi', 'total' ),
		'KH' => __( 'Cambodia', 'total' ),
		'CM' => __( 'Cameroon', 'total' ),
		'CA' => __( 'Canada', 'total' ),
		'CV' => __( 'Cape Verde', 'total' ),
		'KY' => __( 'Cayman Islands', 'total' ),
		'CF' => __( 'Central African Republic', 'total' ),
		'TD' => __( 'Chad', 'total' ),
		'CL' => __( 'Chile', 'total' ),
		'CN' => __( 'China', 'total' ),
		'CX' => __( 'Christmas Island', 'total' ),
		'CC' => __( 'Cocos (Keeling) Islands', 'total' ),
		'CO' => __( 'Colombia', 'total' ),
		'KM' => __( 'Comoros', 'total' ),
		'CG' => __( 'Congo', 'total' ),
		'CD' => __( 'Congo', 'total' ),
		'CK' => __( 'Cook Islands', 'total' ),
		'CR' => __( 'Costa Rica', 'total' ),
		'HR' => __( 'Croatia', 'total' ),
		'CU' => __( 'Cuba', 'total' ),
		'CW' => __( 'Cura&ccedil;ao', 'total' ),
		'CY' => __( 'Cyprus', 'total' ),
		'CZ' => __( 'Czech Republic', 'total' ),
		'DK' => __( 'Denmark', 'total' ),
		'DJ' => __( 'Djibouti', 'total' ),
		'DM' => __( 'Dominica', 'total' ),
		'DO' => __( 'Dominican Republic', 'total' ),
		'EC' => __( 'Ecuador', 'total' ),
		'EG' => __( 'Egypt', 'total' ),
		'SV' => __( 'El Salvador', 'total' ),
		'GQ' => __( 'Equatorial Guinea', 'total' ),
		'ER' => __( 'Eritrea', 'total' ),
		'EE' => __( 'Estonia', 'total' ),
		'ET' => __( 'Ethiopia', 'total' ),
		'FK' => __( 'Falkland Islands', 'total' ),
		'FO' => __( 'Faroe Islands', 'total' ),
		'FJ' => __( 'Fiji', 'total' ),
		'FI' => __( 'Finland', 'total' ),
		'FR' => __( 'France', 'total' ),
		'GF' => __( 'French Guiana', 'total' ),
		'PF' => __( 'French Polynesia', 'total' ),
		'TF' => __( 'French Southern Territories', 'total' ),
		'GA' => __( 'Gabon', 'total' ),
		'GM' => __( 'Gambia', 'total' ),
		'GE' => __( 'Georgia', 'total' ),
		'DE' => __( 'Germany', 'total' ),
		'GH' => __( 'Ghana', 'total' ),
		'GI' => __( 'Gibraltar', 'total' ),
		'GR' => __( 'Greece', 'total' ),
		'GL' => __( 'Greenland', 'total' ),
		'GD' => __( 'Grenada', 'total' ),
		'GP' => __( 'Guadeloupe', 'total' ),
		'GU' => __( 'Guam', 'total' ),
		'GT' => __( 'Guatemala', 'total' ),
		'GG' => __( 'Guernsey', 'total' ),
		'GN' => __( 'Guinea', 'total' ),
		'GW' => __( 'Guinea-Bissau', 'total' ),
		'GY' => __( 'Guyana', 'total' ),
		'HT' => __( 'Haiti', 'total' ),
		'HM' => __( 'Heard Island and McDonald Islands', 'total' ),
		'HN' => __( 'Honduras', 'total' ),
		'HK' => __( 'Hong Kong', 'total' ),
		'HU' => __( 'Hungary', 'total' ),
		'IS' => __( 'Iceland', 'total' ),
		'IN' => __( 'India', 'total' ),
		'ID' => __( 'Indonesia', 'total' ),
		'IR' => __( 'Iran', 'total' ),
		'IQ' => __( 'Iraq', 'total' ),
		'IE' => __( 'Ireland', 'total' ),
		'IM' => __( 'Isle of Man', 'total' ),
		'IL' => __( 'Israel', 'total' ),
		'IT' => __( 'Italy', 'total' ),
		'CI' => __( 'Ivory Coast', 'total' ),
		'JM' => __( 'Jamaica', 'total' ),
		'JP' => __( 'Japan', 'total' ),
		'JE' => __( 'Jersey', 'total' ),
		'JO' => __( 'Jordan', 'total' ),
		'KZ' => __( 'Kazakhstan', 'total' ),
		'KE' => __( 'Kenya', 'total' ),
		'KI' => __( 'Kiribati', 'total' ),
		'KW' => __( 'Kuwait', 'total' ),
		'KG' => __( 'Kyrgyzstan', 'total' ),
		'LA' => __( 'Laos', 'total' ),
		'LV' => __( 'Latvia', 'total' ),
		'LB' => __( 'Lebanon', 'total' ),
		'LS' => __( 'Lesotho', 'total' ),
		'LR' => __( 'Liberia', 'total' ),
		'LY' => __( 'Libya', 'total' ),
		'LI' => __( 'Liechtenstein', 'total' ),
		'LT' => __( 'Lithuania', 'total' ),
		'LU' => __( 'Luxembourg', 'total' ),
		'MO' => __( 'Macao S.A.R., China', 'total' ),
		'MK' => __( 'Macedonia', 'total' ),
		'MG' => __( 'Madagascar', 'total' ),
		'MW' => __( 'Malawi', 'total' ),
		'MY' => __( 'Malaysia', 'total' ),
		'MV' => __( 'Maldives', 'total' ),
		'ML' => __( 'Mali', 'total' ),
		'MT' => __( 'Malta', 'total' ),
		'MH' => __( 'Marshall Islands', 'total' ),
		'MQ' => __( 'Martinique', 'total' ),
		'MR' => __( 'Mauritania', 'total' ),
		'MU' => __( 'Mauritius', 'total' ),
		'YT' => __( 'Mayotte', 'total' ),
		'MX' => __( 'Mexico', 'total' ),
		'FM' => __( 'Micronesia', 'total' ),
		'MD' => __( 'Moldova', 'total' ),
		'MC' => __( 'Monaco', 'total' ),
		'MN' => __( 'Mongolia', 'total' ),
		'ME' => __( 'Montenegro', 'total' ),
		'MS' => __( 'Montserrat', 'total' ),
		'MA' => __( 'Morocco', 'total' ),
		'MZ' => __( 'Mozambique', 'total' ),
		'MM' => __( 'Myanmar', 'total' ),
		'NA' => __( 'Namibia', 'total' ),
		'NR' => __( 'Nauru', 'total' ),
		'NP' => __( 'Nepal', 'total' ),
		'NL' => __( 'Netherlands', 'total' ),
		'NC' => __( 'New Caledonia', 'total' ),
		'NZ' => __( 'New Zealand', 'total' ),
		'NI' => __( 'Nicaragua', 'total' ),
		'NE' => __( 'Niger', 'total' ),
		'NG' => __( 'Nigeria', 'total' ),
		'NU' => __( 'Niue', 'total' ),
		'NF' => __( 'Norfolk Island', 'total' ),
		'MP' => __( 'Northern Mariana Islands', 'total' ),
		'KP' => __( 'North Korea', 'total' ),
		'NO' => __( 'Norway', 'total' ),
		'OM' => __( 'Oman', 'total' ),
		'PK' => __( 'Pakistan', 'total' ),
		'PS' => __( 'Palestinian Territory', 'total' ),
		'PA' => __( 'Panama', 'total' ),
		'PG' => __( 'Papua New Guinea', 'total' ),
		'PY' => __( 'Paraguay', 'total' ),
		'PE' => __( 'Peru', 'total' ),
		'PH' => __( 'Philippines', 'total' ),
		'PN' => __( 'Pitcairn', 'total' ),
		'PL' => __( 'Poland', 'total' ),
		'PT' => __( 'Portugal', 'total' ),
		'PR' => __( 'Puerto Rico', 'total' ),
		'QA' => __( 'Qatar', 'total' ),
		'RE' => __( 'Reunion', 'total' ),
		'RO' => __( 'Romania', 'total' ),
		'RU' => __( 'Russia', 'total' ),
		'RW' => __( 'Rwanda', 'total' ),
		'BL' => __( 'Saint Barth&eacute;lemy', 'total' ),
		'SH' => __( 'Saint Helena', 'total' ),
		'KN' => __( 'Saint Kitts and Nevis', 'total' ),
		'LC' => __( 'Saint Lucia', 'total' ),
		'MF' => __( 'Saint Martin', 'total' ),
		'SX' => __( 'Saint Martin', 'total' ),
		'PM' => __( 'Saint Pierre and Miquelon', 'total' ),
		'VC' => __( 'Saint Vincent and the Grenadines', 'total' ),
		'SM' => __( 'San Marino', 'total' ),
		'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'total' ),
		'SA' => __( 'Saudi Arabia', 'total' ),
		'SN' => __( 'Senegal', 'total' ),
		'RS' => __( 'Serbia', 'total' ),
		'SC' => __( 'Seychelles', 'total' ),
		'SL' => __( 'Sierra Leone', 'total' ),
		'SG' => __( 'Singapore', 'total' ),
		'SK' => __( 'Slovakia', 'total' ),
		'SI' => __( 'Slovenia', 'total' ),
		'SB' => __( 'Solomon Islands', 'total' ),
		'SO' => __( 'Somalia', 'total' ),
		'ZA' => __( 'South Africa', 'total' ),
		'GS' => __( 'South Georgia/Sandwich Islands', 'total' ),
		'KR' => __( 'South Korea', 'total' ),
		'SS' => __( 'South Sudan', 'total' ),
		'ES' => __( 'Spain', 'total' ),
		'LK' => __( 'Sri Lanka', 'total' ),
		'SD' => __( 'Sudan', 'total' ),
		'SR' => __( 'Suriname', 'total' ),
		'SJ' => __( 'Svalbard and Jan Mayen', 'total' ),
		'SZ' => __( 'Swaziland', 'total' ),
		'SE' => __( 'Sweden', 'total' ),
		'CH' => __( 'Switzerland', 'total' ),
		'SY' => __( 'Syria', 'total' ),
		'TW' => __( 'Taiwan', 'total' ),
		'TJ' => __( 'Tajikistan', 'total' ),
		'TZ' => __( 'Tanzania', 'total' ),
		'TH' => __( 'Thailand', 'total' ),
		'TL' => __( 'Timor-Leste', 'total' ),
		'TG' => __( 'Togo', 'total' ),
		'TK' => __( 'Tokelau', 'total' ),
		'TO' => __( 'Tonga', 'total' ),
		'TT' => __( 'Trinidad and Tobago', 'total' ),
		'TN' => __( 'Tunisia', 'total' ),
		'TR' => __( 'Turkey', 'total' ),
		'TM' => __( 'Turkmenistan', 'total' ),
		'TC' => __( 'Turks and Caicos Islands', 'total' ),
		'TV' => __( 'Tuvalu', 'total' ),
		'UG' => __( 'Uganda', 'total' ),
		'UA' => __( 'Ukraine', 'total' ),
		'AE' => __( 'United Arab Emirates', 'total' ),
		'GB' => __( 'United Kingdom', 'total' ),
		'US' => __( 'United States', 'total' ),
		'UM' => __( 'United States Minor Outlying Islands', 'total' ),
		'VI' => __( 'United States Virgin Islands', 'total' ),
		'UY' => __( 'Uruguay', 'total' ),
		'UZ' => __( 'Uzbekistan', 'total' ),
		'VU' => __( 'Vanuatu', 'total' ),
		'VA' => __( 'Vatican', 'total' ),
		'VE' => __( 'Venezuela', 'total' ),
		'VN' => __( 'Vietnam', 'total' ),
		'WF' => __( 'Wallis and Futuna', 'total' ),
		'EH' => __( 'Western Sahara', 'total' ),
		'WS' => __( 'Samoa', 'total' ),
		'YE' => __( 'Yemen', 'total' ),
		'ZM' => __( 'Zambia', 'total' ),
		'ZW' => __( 'Zimbabwe', 'total' ),
	);
}

function easl_get_events_topic_count(){
	global $wpdb;

	$inner_select = " SELECT tt.term_id AS topic_id, p2.ID as pid FROM {$wpdb->posts} AS p2";
	$inner_select .= " LEFT JOIN {$wpdb->term_relationships } AS tr ON p2.ID = tr.object_id";
	$inner_select .= " LEFT JOIN {$wpdb->term_taxonomy } AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id";
	$inner_select .= " WHERE p2.post_type = 'event'";
	$inner_select .= " AND p2.post_status != 'private'";
	$inner_select .= " AND tt.taxonomy = 'event_topic' ";
	
	$query = "SELECT tp.topic_id, mt.meta_value as country_code, COUNT(*) AS num_posts FROM {$wpdb->posts} as p";
	$query .= " LEFT JOIN {$wpdb->postmeta} AS mt ON p.ID = mt.post_id";
	$query .= " LEFT JOIN ({$inner_select}) AS tp ON tp.pid = p.ID";
	$query .= " WHERE p.post_type = 'event'";
	$query .= " AND p.post_status != 'private'";
	$query .= " AND mt.meta_key = 'event_location_country'";
	$query .= " GROUP BY tp.topic_id, mt.meta_value";
	
	$results = (array) $wpdb->get_results( $query, ARRAY_A );
	$topics_country = array();
	foreach($results as $tc_count){
		if($tc_count['num_posts'] < 1){
			continue;
		}
		$topic_id = absint($tc_count['topic_id']);
		if(!isset($topics_country[$topic_id])){
			$topics_country[$topic_id] = array();
		}
		$topics_country[$topic_id][] = $tc_count['country_code'];
	}
	return $topics_country;
}

function easl_is_future_event($event_id) {
	if(!$event_id){
		$event_id = get_the_ID();
	}
	$event_start_date = get_post_meta($event_id, 'event_start_date', true);
	$event_end_date = get_post_meta($event_id, 'event_end_date', true);
	$now_time = time() - 86399;
	if( ($event_start_date > $now_time) && ($event_end_date > $now_time) ){
		return true;
	}
	return false;
}

function easl_get_event_date_parts( $event_id = false ) {
	if ( ! $event_id ) {
		$event_id = get_the_ID();
	}
	$event_start_date = get_post_meta( $event_id, 'event_start_date', true );
	$event_end_date   = get_post_meta( $event_id, 'event_end_date', true );

	if ( ! $event_start_date ) {
		return false;
	}
	$event_start_day = date( 'd', $event_start_date );
	if ( ! $event_start_date ) {
		return false;
	}
	if ( $event_end_date > $event_start_date ) {
		$event_start_day .= '-' . date( 'd', $event_end_date );
	}
	$date_parts       = array(
		'day'   => '',
		'month' => '',
		'year'  => ''
	);
	$date_parts['day'] = $event_start_day;

	$event_start_month = date( 'M', $event_start_date );
	$event_end_month   = '';
	if ( $event_end_date ) {
		$event_end_month = date( 'M', $event_end_date );
	}
	if ( $event_start_month ) {
		$date_parts['month'] = $event_start_month;
	}
	if ( $event_start_month && $event_end_month && ( $event_start_month != $event_end_month ) ) {
		$date_parts['month'] .= '/' . $event_end_month;
	}
	$event_start_year = date( 'Y', $event_start_date );
	if ( $event_start_year ) {
		$date_parts['year'] = $event_start_year;
	}

	return $date_parts;
}

function easl_get_event_time_type( $event_id = false ) {
	if ( ! $event_id ) {
		$event_id = get_the_ID();
	}
	$event_start_date = get_post_meta( $event_id, 'event_start_date', true );
	$event_end_date   = get_post_meta( $event_id, 'event_end_date', true );

	$now_time        = time() - 86399;
	$event_time_type = 'upcoming';
	if ( ( $event_start_date < $now_time ) && ( $event_end_date < $now_time ) ) {
		$event_time_type = 'past';
	}
	if ( ( $event_start_date < $now_time ) && ( $event_end_date >= $now_time ) ) {
		$event_time_type = 'current';
	}

	return $event_time_type;
}

function easl_get_formatted_event_location( $event_id = false ) {
	if ( ! $event_id ) {
		$event_id = get_the_ID();
	}
	$event_location                = array();
	$event_location_venue          = get_post_meta( $event_id, 'event_location_venue', true );
	$event_location_city           = get_post_meta( $event_id, 'event_location_city', true );
	$event_location_country        = get_post_meta( $event_id, 'event_location_country', true );
	$event_location_display_format = get_post_meta( $event_id, 'event_location_display_format', true );

	if ( ! in_array( $event_location_display_format, array(
		'venue|city,contury',
		'venue,Country',
		'venue',
		'city,contury'
	) ) ) {
		$event_location_display_format = 'venue|city,contury';
	}

	$event_location_display = array();

	if ( 'venue|city,contury' == $event_location_display_format ) {
		if ( $event_location_venue ) {
			$event_location_display[] = $event_location_venue;
		}
		if ( $event_location_city ) {
			$event_location[] = $event_location_city;
		}
		if ( $event_location_country ) {
			$event_location[] = easl_event_map_country_key( $event_location_country );
		}
		if ( count( $event_location ) > 0 ) {
			$event_location_display[] = implode( ', ', $event_location );
		}
		$event_location_display = implode( ' | ', $event_location_display );
	} elseif ( 'venue,Country' == $event_location_display_format ) {
		if ( $event_location_venue ) {
			$event_location_display[] = $event_location_venue;
		}
		if ( $event_location_country ) {
			$event_location_display[] = easl_event_map_country_key( $event_location_country );
		}
		$event_location_display = implode( ', ', $event_location_display );
	} elseif ( 'venue' == $event_location_display_format ) {
		$event_location_display = $event_location_venue;
	} elseif ( 'city,contury' == $event_location_display_format ) {
		if ( $event_location_city ) {
			$event_location_display[] = $event_location_city;
		}
		if ( $event_location_country ) {
			$event_location_display[] = easl_event_map_country_key( $event_location_country );
		}
		$event_location_display = implode( ', ', $event_location_display );
	} else {
		$event_location_display = '';
	}

	return $event_location_display;
}