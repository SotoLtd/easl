<?php
if( class_exists('WPBakeryShortCode') ){
	class EASL_VC_News extends WPBakeryShortCode {

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

		public static function suggest_categories( $search_string ) {
			$news_categories = array();
			$categories = get_categories(array(
				'hide_empty' => false,
				'search'     => $search_string,
			));
			if ( $categories ) {
				foreach ( $categories as $cat ) {
					if ( $cat->parent ) {
						$parent = get_term( $cat->parent, 'category' );
						$label = $cat->name .' ('. $parent->name .')';
					} else {
						$label = $cat->name;
					}
					$news_categories[] = array(
						'label' => $label,
						'value' => $cat->term_id,
					);
				}
			}
			return $news_categories;
		}

		public static function render_categories( $data ) {
			$value = $data['value'];
			$term = get_term_by( 'term_id', intval( $value ), 'category' );
			if ( is_object( $term ) ) {
				if ( $term->parent ) {
					$parent = get_term( $term->parent, 'category' );
					$label = $term->name .' ('. $parent->name .')';
				} else {
					$label = $term->name;
				}
				return array(
					'label' => $label,
					'value' => $value,
				);
			}
			return $data;
		}
	}
}
if ( is_admin() ) {
	// Get autocomplete suggestion
	add_filter( 'vc_autocomplete_easl_news_include_categories_callback', array('EASL_VC_News', 'suggest_categories'), 10, 1 );
	// Render autocomplete suggestions
	add_filter( 'vc_autocomplete_easl_news_include_categories_render', array('EASL_VC_News', 'render_categories'), 10, 1 );

	// Get autocomplete suggestion
	add_filter( 'vc_autocomplete_easl_news_exclude_categories_callback', array('EASL_VC_News', 'suggest_categories'), 10, 1 );
	// Render autocomplete suggestions
	add_filter( 'vc_autocomplete_easl_news_exclude_categories_render', array('EASL_VC_News', 'render_categories'), 10, 1 );
};
