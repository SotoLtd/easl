<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Heading_Image_Box extends WPBakeryShortCode {

	/**
	 * Get homepage slider dropdown data
	 * @return array
	 */
	public static function get_popup_template_dd() {
		$_dd     = array( __( 'No popup template found', 'total-child' ) => '' );
		$objects = get_posts( array(
			'post_type'      => EASL_Popup_Template_Config::get_slug(),
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'title',
		) );
		if ( ! $objects ) {
			return $_dd;
		}
		$_dd = array( __( 'Select a popup template', 'total-child' ) => '' );
		foreach ( $objects as $object ) {
			$_dd[ get_the_title( $object->ID ) ] = $object->ID;
		}

		return $_dd;
	}

	public function get_box_id( $heading ) {
		$id = '';
		if ( $heading ) {
			$id = sanitize_html_class( sanitize_title_with_dashes( strtolower( strip_tags( $heading ) ) ) );
		}else{
			$id = vcex_get_unique_id();
		}

		return $id;
	}

	public function get_template_content( $template_id ) {
		$template_post = get_post( $template_id );
		if ( ! $template_post ) {
			return '';
		}

		return wpb_js_remove_wpautop( $template_post->post_content );
	}
}

vc_lean_map( 'easl_heading_image_box', null, get_theme_file_path( 'inc/shortcodes/easl-heading-image-box/map.php' ) );