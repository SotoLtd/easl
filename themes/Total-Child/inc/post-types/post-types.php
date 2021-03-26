<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require_once EASL_INC_DIR . 'post-types/event/event-config.php';
require_once EASL_INC_DIR . 'post-types/fellowship/fellowship-config.php';
require_once EASL_INC_DIR . 'post-types/associations/associations-config.php';
require_once EASL_INC_DIR . 'post-types/publication/publication-config.php';
require_once EASL_INC_DIR . 'post-types/annual-reports/annual-reports-config.php';
require_once EASL_INC_DIR . 'post-types/slide-decks/slide-decks-config.php';
require_once EASL_INC_DIR . 'post-types/newsletter/newsletter-config.php';
require_once EASL_INC_DIR . 'post-types/ilc/ilc-config.php';
require_once EASL_INC_DIR . 'post-types/award/award-config.php';
require_once EASL_INC_DIR . 'post-types/secretary-generals/secretary-generals-config.php';
require_once EASL_INC_DIR . 'post-types/easl-history/easl-history-config.php';
require_once EASL_INC_DIR . 'post-types/membership-category/membership-category-config.php';
require_once EASL_INC_DIR . 'post-types/easl-widget/easl-widget-config.php';
require_once EASL_INC_DIR . 'post-types/icon-widget/icon-widget-config.php';
require_once EASL_INC_DIR . 'post-types/home-page-slider/home-page-slider.php';
require_once EASL_INC_DIR . 'post-types/popup-template/popup-template.php';
require_once EASL_INC_DIR . 'post-types/event-subpage/event-subpage.php';
require_once EASL_INC_DIR . 'post-types/blog/blog-config.php';

function easl_change_pt_labels_post($labels) {
	$labels = array(
		'name' => _x('News', 'post type general name'),
		'singular_name' => _x('News', 'post type singular name'),
		'add_new' => _x('Add New', 'post'),
		'add_new_item' => __('Add New News'),
		'edit_item' => __('Edit News'),
		'new_item' => __('New News'),
		'view_item' => __('View News'),
		'view_items' => __('View News'),
		'search_items' => __('Search News'),
		'not_found' => __('No news found.'),
		'not_found_in_trash' => __('No news found in Trash.'),
		'all_items' => __( 'All News' ),
		'archives' => __( 'News Archives' ),
		'attributes' => __( 'News Attributes' ),
		'insert_into_item' => __( 'Insert into news' ),
		'uploaded_to_this_item' => __( 'Uploaded to this news' ),
		'filter_items_list' => __( 'Filter news list' ),
		'items_list_navigation' => __( 'News list navigation' ),
		'items_list' => __( 'News list' ),
		'menu_name' => _x('News', 'post type general name'),
		'name_admin_bar' => _x( 'News', 'add new from admin bar' ),
	);
	return $labels;
}
add_filter('post_type_labels_post', 'easl_change_pt_labels_post', 10);

function easl_admin_menu_change() {
	global $menu, $submenu, $pagenow, $title;
	// Rename menu
	$to_rename = array(
		//'revslider' => 'Homepage Sliders'
	);
	foreach ( $menu as $id => $data ) {
		if ( isset( $to_rename[$data[ 2 ]] ) ) {
			$menu[ $id ][0] = $to_rename[$data[ 2 ]];
			$menu[ $id ][3] = $to_rename[$data[ 2 ]];
		}
	}
}
add_action( 'admin_menu', 'easl_admin_menu_change', 200 );

add_filter( 'custom_menu_order', '__return_true' );
function easl_menu_order($menu_order) {
	$separator1_position	 = array_search( 'separator1', $menu_order );
	if('false' === $separator1_position) {
		$separator1_position = 1;
	}
	$front_end_menus = array(
		'edit.php?post_type=annual_reports', // Annual Reports
		'edit.php?post_type=award', // Awards
		'edit.php?post_type=blog', // Awards
		'edit.php?post_type=event', // Events
		'edit.php?post_type=easl_widget', // Events
		'edit.php?post_type=fellowship', // Fellowship
		'edit.php?post_type=easl_hp_slider', // Home page sliders
		//'revslider', // Home page sliders
		'edit.php?post_type=easl_history', // History
		'edit.php?post_type=ilc', // ILC
		'edit.php?post_type=easl_icon_widget', // Icon Widget
		'edit.php?post_type=membership_category', // Membership Categories
		'edit.php?post_type=associations', // National Associations
		'edit.php',// News/posts
		'edit.php?post_type=newsletter', // Newsletters
		'edit.php?post_type=page', // Pages
		'edit.php?post_type=publication', // Publications
		'edit.php?post_type=staff', // People
		'edit.php?post_type=secretary_generals', // Secretary General
		'edit.php?post_type=slide_decks', // Slide Decks
        'edit.php?post_type=testimonials', // Testimonial
	);
	foreach ($front_end_menus as $menu_id) {
		$position = array_search($menu_id, $menu_order);
		if('false' != $position) {
			unset($menu_order[$position]);
		}
	}
	array_splice( $menu_order, $separator1_position, 0, $front_end_menus );
	return $menu_order;

}
add_filter( 'menu_order', 'easl_menu_order' );

function easl_testimonials_args( $args ) {
    $args['public']      = false;
    $args['show_ui']     = true;
    $args['has_archive'] = false;
    $args['rewrite']     = false;
    
    return $args;
}

add_filter( 'wpex_testimonials_args', 'easl_testimonials_args' );
function easl_testimonials_category_args( $args ) {
    $args['public']            = false;
    $args['show_ui']           = true;
    $args['show_in_nav_menus'] = false;
    $args['show_tagcloud']     = false;
    $args['query_var']         = false;
    $args['rewrite']     = false;
    
    return $args;
}
add_filter( 'wpex_taxonomy_testimonials_category_args', 'easl_testimonials_category_args' );

function easl_post_type_editor_types($types) {
    return array();
}
add_filter('wpex_post_type_editor_types', 'easl_post_type_editor_types');