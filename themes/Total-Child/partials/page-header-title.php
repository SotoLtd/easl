<?php
/**
 * Returns the post title
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define title args
$args = array();

// Check if singular
$is_singular = is_singular();
$post_type   = $is_singular ? get_post_type() : '';
$instance    = $post_type ? 'singular_' . $post_type : '';

// Single post markup
if ( 'post' == $post_type ) {
	$blog_single_header = wpex_get_mod( 'blog_single_header', 'custom_text' );
	if ( 'custom_text' == $blog_single_header || 'first_category' == $blog_single_header ) {
		$args['html_tag'] = 'span';
		$args['schema_markup'] = '';
	}
}

// Singular CPT
elseif ( $is_singular && ! in_array( $post_type, array( 'page', 'attachment' ) ) ) {
	$args['html_tag'] = 'span';
	$args['schema_markup'] = '';
}

// Apply filters
$args = apply_filters( 'wpex_page_header_title_args', $args, $instance );

// Parse args to prevent empty attributes and extract
extract( wp_parse_args( $args, array(
	'html_tag'      => 'h1',
	'string'        => wpex_title(),
	'schema_markup' => wpex_get_schema_markup( 'headline' )
) ) );

// If string is empty return
if ( empty( $string ) ) {
	return;
}

// Sanitize
$html_tag = wp_strip_all_tags( $html_tag );

$allow_html_shortcode = false;
if(is_page()){
	$custom_title = trim(get_post_meta(get_the_ID(), 'wpex_post_title', true));
	$allow_settings = get_post_meta(get_the_ID(), 'easl_title_allow_shortcode_html', true);
	if($custom_title && 'enable' == $allow_settings){
		$allow_html_shortcode = true;
	}
}
echo '<div class="easl-page-header-title-wrap">';
if(('background-image' == wpex_page_header_style()) && wpex_page_header_background_image()) {
	echo easl_page_header_background_image();
}else{
	echo easl_singular_default_header_background_image();
}
// Output title
echo '<' . $html_tag . ' class="page-header-title wpex-clr"' . $schema_markup . '>';
	$back_url = '';
	if(is_singular(EASL_Event_Config::get_event_slug())){
		$back_url = wpex_get_mod( 'event_header_back_button', '');
	}elseif(is_singular(Fellowship_Config::get_fellowship_slug())){
		$back_url = wpex_get_mod( 'fellowship_header_back_button', '');
	}elseif(is_singular(Publication_Config::get_publication_slug())){
		$back_url = wpex_get_mod( 'publications_header_back_button', '');
	}elseif(is_single()){
		$back_url = get_the_permalink(wpex_get_mod( 'blog_page', 5626));
		echo '<a class="easl-title-back-link" href="'. get_the_permalink($page_id) .'"><span class="ticon ticon-angle-left" aria-hidden="true"></span> ' . __('Back', 'total-child') . '</a>';
	}
	if($back_url){
		echo '<a class="easl-title-back-link" href="'. esc_url($back_url) .'"><span class="ticon ticon-angle-left" aria-hidden="true"></span> ' . __('Back', 'total-child') . '</a>';
	}
	if($allow_html_shortcode){
		echo do_shortcode($string);
	}else{
		echo '<span>' . wp_kses_post( $string ) . '</span>';
	}

echo '</' . $html_tag . '>';
echo '</div>';