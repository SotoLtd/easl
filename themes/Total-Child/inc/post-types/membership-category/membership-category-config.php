<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_Membership_Category_Config {

	protected static $slugs = array(
		'type' => 'membership_category',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		add_action( 'init', array( 'EASL_Membership_Category_Config', 'register_post_type' ), 0 );
	}

	/**
	 * Get post type slug
	 * @return string
	 */
	public static function get_slug(){
		return self::$slugs['type'];
	}
	/**
	 * Register post type.
	 */
	public static function register_post_type() {
		register_post_type( self::get_slug(), array(
			'labels' => array(
				'name' => __( 'Membership Categories', 'total-child' ),
				'singular_name' => __( 'Membership Category', 'total-child' ),
				'add_new' => __( 'Add New', 'total-child' ),
				'add_new_item' => __( 'Add New Membership Category', 'total-child' ),
				'edit_item' => __( 'Edit Membership Category', 'total-child' ),
				'new_item' => __( 'Add New Membership Category', 'total-child' ),
				'view_item' => __( 'View Membership Category', 'total-child' ),
				'search_items' => __( 'Search Membership Categories', 'total-child' ),
				'not_found' => __( 'No Membership Categories Found', 'total-child' ),
				'not_found_in_trash' => __( 'No Membership Categories Found In Trash', 'total-child' )
			),
			'public' => false,
			'show_ui' => true,
			'capability_type' => 'post',
			'has_archive' => false,
			'menu_position' => 25,
			'rewrite' => false,
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'page-attributes',
			),
		) );
	}
}

new EASL_Membership_Category_Config();