<?php
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class EASL_VC_News_List extends WPBakeryShortCode {
		public function get_years() {
			global $wpdb;
			$years = $wpdb->get_col( "SELECT DISTINCT YEAR( post_date ) AS year FROM $wpdb->posts WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC" );
			if(!$years || !is_array($years)){
				$years = array();
			}
			return $years;
		}

		public function string_to_array( $value ) {

			// Return if value is empty
			if ( !$value ) {
				return;
			}

			// Return if already an array
			if ( is_array( $value ) ) {
				return $value;
			}

			// Define output array
			$array = array();

			// Clean up value
			$items = preg_split( '/\,[\s]*/', $value );

			// Create array
			foreach ( $items as $item ) {
				if ( strlen( $item ) > 0 ) {
					$array[] = $item;
				}
			}

			// Return array
			return $array;
		}
	}
}
if ( is_admin() ) {
	// Get autocomplete suggestion
	add_filter( 'vc_autocomplete_easl_news_list_include_categories_callback', array('EASL_VC_News', 'suggest_categories'), 10, 1 );
	// Render autocomplete suggestions
	add_filter( 'vc_autocomplete_easl_news_list_include_categories_render', array('EASL_VC_News', 'render_categories'), 10, 1 );

	// Get autocomplete suggestion
	add_filter( 'vc_autocomplete_easl_news_list_exclude_categories_callback', array('EASL_VC_News', 'suggest_categories'), 10, 1 );
	// Render autocomplete suggestions
	add_filter( 'vc_autocomplete_easl_news_list_exclude_categories_render', array('EASL_VC_News', 'render_categories'), 10, 1 );
};
