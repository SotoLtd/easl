<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


if ( class_exists( 'WPBakeryShortCode' ) ) {
	class EASL_VC_Yearly_Awardee extends WPBakeryShortCode {


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

		public static function autocomplete_suggest_award( $search_string ) {
			$awards = array();
			$get_awards = get_posts(array(
				'post_type' => EASL_Award_Config::get_slug(),
				's' => $search_string,
				'posts_per_page' => -1,
			));
			if ( $get_awards ) {
				foreach ( $get_awards as $award ) {
					$awards[] = array(
						'label' => get_the_title($award),
						'value' => $award->ID,
					);
				}
			}
			return $awards;
		}

		public static function autocomplete_render_award( $data ) {
			$value = $data['value'];
			$award = get_post(intval( $value ));
			if ( is_object( $award ) ) {
				return array(
					'label' => get_the_title($award),
					'value' => $award->ID,
				);
			}
			return $data;
		}
		public static function autocomplete_award_types( $search_string ) {
			$staff_tags = array();
			$get_terms  = get_terms(
				EASL_Award_Config::get_award_group_slug(),
				array(
					'hide_empty' => false,
					'search'     => $search_string,
				) );
			if ( $get_terms ) {
				foreach ( $get_terms as $term ) {
					if ( $term->parent ) {
						$parent = get_term( $term->parent, EASL_Award_Config::get_award_group_slug() );
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

		public static function autocomplete_render_award_types( $data ) {
			$value = $data['value'];
			$term  = get_term_by( 'term_id', intval( $value ), EASL_Award_Config::get_award_group_slug() );
			if ( is_object( $term ) ) {
				if ( $term->parent ) {
					$parent = get_term( $term->parent, EASL_Award_Config::get_award_group_slug() );
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

		// Get autocomplete suggestion for awards
		add_filter( 'vc_autocomplete_easl_yearly_awardees_include_awards_callback', array(
			'EASL_VC_Yearly_Awardee',
			'autocomplete_suggest_award'
		), 10, 1 );

		// Render autocomplete suggestions for awards
		add_filter( 'vc_autocomplete_easl_yearly_awardees_include_awards_render', array(
			'EASL_VC_Yearly_Awardee',
			'autocomplete_render_award'
		), 10, 1 );

		// Get autocomplete suggestion
		add_filter( 'vc_autocomplete_easl_yearly_awardees_award_type_callback', array(
			'EASL_VC_Yearly_Awardee',
			'autocomplete_award_types'
		), 10, 1 );

		// Render autocomplete suggestions
		add_filter( 'vc_autocomplete_easl_yearly_awardees_award_type_render', array(
			'EASL_VC_Yearly_Awardee',
			'autocomplete_render_award_types'
		), 10, 1 );
	}
}
