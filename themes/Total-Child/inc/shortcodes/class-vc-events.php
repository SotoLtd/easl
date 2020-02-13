<?php
if( class_exists('WPBakeryShortCode') ){
	class EASL_VC_Events extends WPBakeryShortCode {
		/**
		 * Get array for comma sepaarted values
		 * @param $value
		 *
		 * @return array
		 */
		public function string_to_array( $value ) {

			// Return if value is empty
			if ( !$value ) {
				return array();
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
		public static function autocomplete_suggest_topcis($search_string) {
			$topics	 = array();
			$topic_terms				 = get_terms(array(
				'taxonomy' => EASL_Event_Config::get_topic_slug(),
				'hide_empty' => false,
				'search'	 => $search_string,
			) );
			if(!$topic_terms){
				return $topics;
			}
			foreach ( $topic_terms as $term ) {
				if ( $term->parent ) {
					$parent	 = get_term( $term->parent, EASL_Event_Config::get_topic_slug() );
					$label	 = $term->name . ' (' . $parent->name . ')';
				} else {
					$label = $term->name;
				}
				$topics[] = array(
					'label'	 => $label,
					'value'	 => $term->term_id,
				);
			}
			return $topics;
		}
		public static function autocomplete_suggest_meeting_types($search_string) {
			$meeting_types	 = array();
			$meeting_types_terms				 = get_terms(array(
				'taxonomy' => EASL_Event_Config::get_meeting_type_slug(),
				'hide_empty' => false,
				'search'	 => $search_string,
			) );
			if(!$meeting_types_terms){
				return $meeting_types;
			}
			foreach ( $meeting_types_terms as $term ) {
				if ( $term->parent ) {
					$parent	 = get_term( $term->parent, EASL_Event_Config::get_meeting_type_slug() );
					$label	 = $term->name . ' (' . $parent->name . ')';
				} else {
					$label = $term->name;
				}
				$meeting_types[] = array(
					'label'	 => $label,
					'value'	 => $term->term_id,
				);
			}
			return $meeting_types;
		}
		public static function autocomplete_render_topics($data) {
			$value	 = $data[ 'value' ];
			$term	 = get_term_by( 'term_id', intval( $value ), EASL_Event_Config::get_topic_slug() );
			if ( is_object( $term ) ) {
				if ( $term->parent ) {
					$parent	 = get_term( $term->parent, EASL_Event_Config::get_topic_slug() );
					$label	 = $term->name . ' (' . $parent->name . ')';
				} else {
					$label = $term->name;
				}
				return array(
					'label'	 => $label,
					'value'	 => $value,
				);
			}
			return $data;
		}
		public static function autocomplete_render_meeting_types($data) {
			$value	 = $data[ 'value' ];
			$term	 = get_term_by( 'term_id', intval( $value ), EASL_Event_Config::get_meeting_type_slug() );
			if ( is_object( $term ) ) {
				if ( $term->parent ) {
					$parent	 = get_term( $term->parent, EASL_Event_Config::get_meeting_type_slug() );
					$label	 = $term->name . ' (' . $parent->name . ')';
				} else {
					$label = $term->name;
				}
				return array(
					'label'	 => $label,
					'value'	 => $value,
				);
			}
			return $data;
		}
	}
}

// Admin filters
if ( is_admin() ) {

	// Get autocomplete suggestion
	add_filter( 'vc_autocomplete_easl_events_topics_callback', array( 'EASL_VC_Events', 'autocomplete_suggest_topcis' ), 10, 1 );
	add_filter( 'vc_autocomplete_easl_events_meeting_types_callback', array( 'EASL_VC_Events', 'autocomplete_suggest_meeting_types' ), 10, 1 );

	// Render autocomplete suggestions
	add_filter( 'vc_autocomplete_easl_events_topics_render', array( 'EASL_VC_Events', 'autocomplete_render_topics' ), 10, 1 );
	add_filter( 'vc_autocomplete_easl_events_meeting_types_render', array( 'EASL_VC_Events', 'autocomplete_render_meeting_types' ), 10, 1 );
}
