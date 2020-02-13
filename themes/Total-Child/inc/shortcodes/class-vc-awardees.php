<?php


if ( class_exists( 'WPBakeryShortCode' ) ) {
	class EASL_VC_Awardees extends WPBakeryShortCode {
		public function __construct( $settings ) {
			// Admin filters

			parent::__construct( $settings );
		}

		public function string_to_array( $value ) {

			// Return if value is empty
			if ( ! $value ) {
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

		public static function vcex_staff_tags( $search_string ) {
			$staff_tags = array();
			$get_terms  = get_terms(
				'staff_tag',
				array(
					'hide_empty' => false,
					'search'     => $search_string,
				) );
			if ( $get_terms ) {
				foreach ( $get_terms as $term ) {
					if ( $term->parent ) {
						$parent = get_term( $term->parent, 'staff_tag' );
						$label  = $term->name . ' (' . $parent->name . ')';
					} else {
						$label = $term->name;
					}
					$staff_tags[] = array(
						'label' => $label,
						'value' => $term->term_id,
					);
				}
			}

			return $staff_tags;
		}

		public static function vcex_render_staff_tags( $data ) {
			$value = $data['value'];
			$term  = get_term_by( 'term_id', intval( $value ), 'staff_tag' );
			if ( is_object( $term ) ) {
				if ( $term->parent ) {
					$parent = get_term( $term->parent, 'staff_tag' );
					$label  = $term->name . ' (' . $parent->name . ')';
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

	if ( is_admin() ) {

		// Get autocomplete suggestion
		add_filter( 'vc_autocomplete_easl_awardees_include_tags_callback', array(
			'EASL_VC_Awardees',
			'vcex_staff_tags'
		), 10, 1 );
		add_filter( 'vc_autocomplete_easl_awardees_exclude_tags_callback', array(
			'EASL_VC_Awardees',
			'vcex_staff_tags'
		), 10, 1 );
		add_filter( 'vc_autocomplete_easl_awardees_filter_active_tags_callback', array(
			'EASL_VC_Awardees',
			'vcex_staff_tags'
		), 10, 1 );

		// Render autocomplete suggestions
		add_filter( 'vc_autocomplete_easl_awardees_include_tags_render', array(
			'EASL_VC_Awardees',
			'vcex_render_staff_tags'
		), 10, 1 );
		add_filter( 'vc_autocomplete_easl_awardees_exclude_tags_render', array(
			'EASL_VC_Awardees',
			'vcex_render_staff_tags'
		), 10, 1 );
		add_filter( 'vc_autocomplete_easl_awardees_filter_active_tags_render', array(
			'EASL_VC_Awardees',
			'vcex_render_staff_tags'
		), 10, 1 );
	}
}
