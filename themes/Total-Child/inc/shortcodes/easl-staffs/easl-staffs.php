<?php
/**
 * EASL_VC_Staffs
 */
if ( class_exists( 'WPBakeryShortCode' ) ) {

	class EASL_VC_Staffs extends WPBakeryShortCode {

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

		public function get_staff_profile_thumb($id) {
			$attachment_id = get_post_thumbnail_id($id);
			if(!$attachment_id){
				return '';
			}
			$attachment_src = wp_get_attachment_image_src($attachment_id, 'staff_grid');
			if(!$attachment_src) {
				return '';
			}
			return sprintf('<img src="%s" alt="" width="254" height="254"/>', $attachment_src[0]);
		}

		public function parse_info_template( $template, $id ) {
			$template = trim( $template );
			if ( !$template ) {
				return '';
			}
			$parsed_output	 = '';
			$staff_post		 = get_post( $id );
			if ( !$staff_post ) {
				return '';
			}
			if ( function_exists( 'get_field' ) ) {
				$telephone	 = get_field( 'telephone', $id );
				$fax		 = get_field( 'fax', $id );
				$email		 = get_field( 'email', $id );
				$city		 = get_field( 'city', $id );
				$country	 = get_field( 'country', $id );
				$email		 = get_field( 'email', $id );
				$job_title		 = get_field( 'job_title', $id );
				$easl_posiiton		 = get_field( 'easl_posiiton', $id );
			} else {
				$telephone	 = get_post_meta( $id, 'telephone', true );
				$fax		 = get_post_meta( $id, 'fax', true );
				$email		 = get_post_meta( $id, 'email', true );
				$city		 = get_post_meta( $id, 'city', true );
				$country	 = get_post_meta( $id, 'country', true );
				$email		 = get_post_meta( $id, 'email', true );
				$job_title		 = get_post_meta( $id, 'job_title', true );
				$easl_posiiton		 = get_post_meta( $id, 'easl_posiiton', true );
			}
			$excerpt		 = $staff_post->post_excerpt;
			$name			 = get_the_title( $id );
			$contact_info	 = '';

			if ( $telephone ) {
				$contact_info .= sprintf( '<p class="easl-staff-telephone"><a href="tel:%s">%s</a></p>', $telephone, $telephone );
			}
			if ( $fax ) {
				$contact_info .= sprintf( '<p class="easl-staff-fax"><span>%s</span></p>', $fax, $fax );
			}
			if ( $email ) {
				$contact_info .= sprintf( '<p class="easl-staff-email"><a href="mailto:%s">%s</a></p>', $email, $email );
			}

			$replaces = array(
				'{{telephone}}'		 => $telephone,
				'{{fax}}'			 => $fax,
				'{{email}}'			 => $email,
				'{{contact_info}}'	 => $contact_info,
				'{{city}}'			 => $city,
				'{{country}}'		 => $country,
				'{{excerpt}}'		 => $excerpt,
				'{{name}}'			 => $name,
				'{{job_title}}'		=> $job_title,
				'{{easl_posiiton}}'		=> $easl_posiiton,
			);
			$parsed_output = str_replace(array_keys($replaces), array_values($replaces), $template);
			return trim($parsed_output);
		}

		public static function get_staff_fields() {
			return array(
				'telephone',
				'fax',
				'email',
				'contact_info',
				'city',
				'country',
				'excerpt',
				'name',
				'job_title',
				'easl_posiiton',
			);
		}

	}

}

if ( is_admin() ) {
	// Get autocomplete suggestion
	add_filter( 'vc_autocomplete_easl_staffs_include_categories_callback', 'vcex_suggest_staff_categories', 10, 1 );
	// Render autocomplete suggestions
	add_filter( 'vc_autocomplete_easl_staffs_include_categories_render', 'vcex_render_staff_categories', 10, 1 );
};



vc_lean_map('easl_staffs', null, get_theme_file_path('inc/shortcodes/easl-staffs/map.php'));