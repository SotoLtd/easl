<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_Secretary_Generals_Config {

	protected static $slugs = array(
		'type' => 'secretary_generals',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		add_action( 'init', array( 'EASL_Secretary_Generals_Config', 'register_post_type' ), 0 );
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
				'name' => __( 'Secretary Generals', 'total-child' ),
				'singular_name' => __( 'Secretary General', 'total-child' ),
				'add_new' => __( 'Add New', 'total-child' ),
				'add_new_item' => __( 'Add New Secretary General', 'total-child' ),
				'edit_item' => __( 'Edit Secretary General', 'total-child' ),
				'new_item' => __( 'Add New Secretary General', 'total-child' ),
				'view_item' => __( 'View Secretary General', 'total-child' ),
				'search_items' => __( 'Search Secretary Generals', 'total-child' ),
				'not_found' => __( 'No Secretary Generals Found', 'total-child' ),
				'not_found_in_trash' => __( 'No Secretary Generals Found In Trash', 'total-child' )
			),
			'public' => false,
			'show_ui' => true,
			'capability_type' => 'post',
			'has_archive' => false,
			'menu_position' => 25,
			'rewrite' => false,
			'supports' => array(
				'title',
				'thumbnail',
			),
		) );
	}
}

new EASL_Secretary_Generals_Config();
