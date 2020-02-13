<?php

/**
 * EASL_VC_Staffs
 */
if ( class_exists( 'WPBakeryShortCode' ) ) {

	class EASL_VC_Staffs_Accordion extends WPBakeryShortCode {

		public function enqueue_css_js() {

			//wp_enqueue_style( 'easl-staff-list-style', get_stylesheet_directory_uri() . '/assets/css/community.css' );

			wp_enqueue_script( 'easl-staff-list-script', get_stylesheet_directory_uri() . '/assets/js/community.js', array( 'jquery' ), false, true );
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

		public function build_category_query( $include_categories, $cat_relation = "IN" ) {
			$cats_ids = $this->string_to_array( $include_categories );
			if ( empty( $cats_ids ) ) {
				return '';
			}
			if ( !in_array( $cat_relation, array( 'IN', 'NOT IN', 'AND', 'EXISTS' and 'NOT EXISTS' ) ) ) {
				$cat_relation = 'AND';
			}
			$tax_query = array(
				'taxonomy'	 => 'staff_category',
				'field'		 => 'term_id',
				'terms'		 => $cats_ids,
			);
			if ( count( $cats_ids ) > 1 ) {
				$tax_query[ 'operator' ] = $cat_relation;
			}
			return array( $tax_query );
		}

		public function staff_has_details( $staff_id ) {
			$staff_post = get_post( $staff_id );
			if ( !$staff_post ) {
				return false;
			}
			if ( !trim( $staff_post->post_content ) ) {
				return false;
			}
			return true;
		}
		
		public function get_staff_profile_thumb($id, $class="") {
			$attachment_id = get_post_thumbnail_id($id);
			if(!$attachment_id){
				return '';
			}
			$attachment_src = wp_get_attachment_image_src($attachment_id, 'staff_grid');
			if(!$attachment_src) {
				return '';
			}
			if($class){
				$class = 'easl-staff-accordion-staff-image ' . $class;
			}else{
				$class = 'easl-staff-accordion-staff-image';
			}
			return sprintf('<img class="%s" src="%s" alt="" width="254" height="254"/>', $class, $attachment_src[0]);
		}

		public static function suggest_staffs( $search_string ) {
			$staffs = array();
			$get_staffs = get_posts(array(
				'post_type' => 'staff',
				's' => $search_string,
				'posts_per_page' => -1,
			));
			if ( $get_staffs ) {
				foreach ( $get_staffs as $staff ) {
					$staffs[] = array(
						'label' => get_the_title($staff),
						'value' => $staff->ID,
					);
				}
			}
			return $staffs;
		}

		public static function render_staffs( $data ) {
			$value = $data['value'];
			$staff = get_post(intval( $value ));
			if ( is_object( $staff ) ) {
				return array(
					'label' => get_the_title($staff),
					'value' => $staff->ID,
				);
			}
			return $data;
		}

		public static function suggest_staff_categories( $search_string ) {
			$staff_categories = array();
			$get_terms = get_terms(
				'staff_category',
				array(
					'hide_empty' => false,
					'search'     => $search_string,
				) );
			if ( $get_terms ) {
				foreach ( $get_terms as $term ) {
					if ( $term->parent ) {
						$parent = get_term( $term->parent, 'staff_category' );
						$label = $term->name .' ('. $parent->name .')';
					} else {
						$label = $term->name;
					}
					$staff_categories[] = array(
						'label' => $label,
						'value' => $term->term_id,
					);
				}
			}
			return $staff_categories;
		}

		public static function render_staff_categories( $data ) {
			$value = $data['value'];
			$term = get_term_by( 'term_id', intval( $value ), 'staff_category' );
			if ( is_object( $term ) ) {
				if ( $term->parent ) {
					$parent = get_term( $term->parent, 'staff_category' );
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

	if ( is_admin() ) {// Get autocomplete suggestion
		add_filter( 'vc_autocomplete_easl_staffs_accordion_include_stuffs_callback', array('EASL_VC_Staffs_Accordion', 'suggest_staffs'), 10, 1 );
		// Render autocomplete suggestions
		add_filter( 'vc_autocomplete_easl_staffs_accordion_include_stuffs_render', array('EASL_VC_Staffs_Accordion', 'render_staffs'), 10, 1 );
		// Get autocomplete suggestion
		add_filter( 'vc_autocomplete_easl_staffs_accordion_include_categories_callback', array('EASL_VC_Staffs_Accordion', 'suggest_staff_categories'), 10, 1 );
		// Render autocomplete suggestions
		add_filter( 'vc_autocomplete_easl_staffs_accordion_include_categories_render', array('EASL_VC_Staffs_Accordion', 'render_staff_categories'), 10, 1 );
	}
}