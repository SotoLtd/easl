<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_History_Config {

	protected static $slugs = array(
		'type' => 'easl_history',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		add_action( 'init', array( 'EASL_History_Config', 'register_post_type' ), 0 );
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
				'name' => __( 'Histories', 'total-child' ),
				'singular_name' => __( 'History', 'total-child' ),
				'add_new' => __( 'Add New', 'total-child' ),
				'add_new_item' => __( 'Add New History', 'total-child' ),
				'edit_item' => __( 'Edit History', 'total-child' ),
				'new_item' => __( 'Add New History', 'total-child' ),
				'view_item' => __( 'View History', 'total-child' ),
				'search_items' => __( 'Search Histories', 'total-child' ),
				'not_found' => __( 'No Histories Found', 'total-child' ),
				'not_found_in_trash' => __( 'No Histories Found In Trash', 'total-child' )
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
				'excerpt',
				'thumbnail',
				'revisions',
			),
		) );
	}
}

new EASL_History_Config();