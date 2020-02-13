<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


if ( class_exists( 'WPBakeryShortCode' ) ) {
	class EASL_VC_Slide_Decks extends WPBakeryShortCode {
		public function get_categories_heirarchi() {
			$categories       = get_terms( array(
				'taxonomy'     => Slide_Decks_Config::get_category_slug(),
				'hierarchical' => true,
				'hide_empty'   => false,
				'orderby'      => 'name',
				'order'        => 'DESC',
			) );
			$top_level_cats   = array();
			$child_level_cats = array();
			foreach ( $categories as $c ) {
				if ( empty( $c->parent ) ) {
					$top_level_cats[] = array(
						'term_id'    => $c->term_id,
						'term_name' => $c->name,
					);
				} else {
					if(!isset($child_level_cats[ $c->parent ])){
						$child_level_cats[ $c->parent ] = array();
					}
					$child_level_cats[ $c->parent ][] = array(
						'term_id'    => $c->term_id,
						'term_name' => $c->name,
					);
				}
			}
			if ( empty( $top_level_cats ) ) {
				return false;
			}

			return array(
				'parents' => array_reverse($top_level_cats),
				'childs'  => $child_level_cats,
			);
		}
	}
}
