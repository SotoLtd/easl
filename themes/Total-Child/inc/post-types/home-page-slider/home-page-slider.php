<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_Homepage_Slider_Config {

	protected static $slugs = array(
		'type' => 'easl_hp_slider',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		add_action( 'init', array( 'EASL_Homepage_Slider_Config', 'register_post_type' ), 0 );
	}

	/**
	 * Get post type slug
	 * @return string
	 */
	public static function get_slug() {
		return self::$slugs['type'];
	}

	/**
	 * Register post type.
	 */
	public static function register_post_type() {
		register_post_type( self::get_slug(), array(
			'labels'          => array(
				'name'               => __( 'Homepage Sliders', 'total-child' ),
				'singular_name'      => __( 'Homepage Slider', 'total-child' ),
				'add_new'            => __( 'Add New', 'total-child' ),
				'add_new_item'       => __( 'Add New Homepage Slider', 'total-child' ),
				'edit_item'          => __( 'Edit Homepage Slider', 'total-child' ),
				'new_item'           => __( 'Add New Homepage Slider', 'total-child' ),
				'view_item'          => __( 'View Homepage Slider', 'total-child' ),
				'search_items'       => __( 'Search Homepage Sliders', 'total-child' ),
				'not_found'          => __( 'No Homepage Sliders Found', 'total-child' ),
				'not_found_in_trash' => __( 'No Homepage Sliders Found In Trash', 'total-child' )
			),
			'public'          => false,
			'show_ui'         => true,
			'capability_type' => 'post',
			'has_archive'     => false,
			'menu_position'   => 25,
			'rewrite'         => false,
			'supports'        => array(
				'title'
			),
		) );
	}
}

new EASL_Homepage_Slider_Config();