<?php
/**
 * Newletter Post Type Configuration file
 *
 * @package EASL Website
 * @subpackage Event Functions
 */
// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}
class EASL_Newsletter_Config {
	protected static $slugs = array(
		'newsletter' => 'newsletter',
	);
	/**
	 * Get thing started
	 */
	public function __construct() {
		// Adds the portfolio post type
		add_action( 'init', array( 'EASL_Newsletter_Config', 'register_post_type' ), 0 );

	}

	public static function get_slug(){
		return self::$slugs['newsletter'];
	}
	/**
	 * Register post type.
	 *
	 * @since 2.0.0
	 */
	public static function register_post_type() {
		register_post_type( self::get_slug(), array(
			'labels' => array(
				'name' => __( 'Newsletters', 'total' ),
				'singular_name' => __( 'Newsletter', 'total' ),
				'add_new' => __( 'Add New', 'total' ),
				'add_new_item' => __( 'Add New Newsletter', 'total' ),
				'edit_item' => __( 'Edit Newsletter', 'total' ),
				'new_item' => __( 'Add New Newsletter', 'total' ),
				'view_item' => __( 'View Newsletter', 'total' ),
				'search_items' => __( 'Search Newsletters', 'total' ),
				'not_found' => __( 'No Newsletter Found', 'total' ),
				'not_found_in_trash' => __( 'No Newsletters Found In Trash', 'total' )
			),
			'public' => false,
			'show_ui' => true,
			'capability_type' => 'post',
			'has_archive' => false,
			'menu_icon' => 'dashicons-media-document',
			'menu_position' => 5,
			'rewrite' => false,
			'supports' => array(
				'title',
				'thumbnail',
			),
		) );
	}

	public static function get_years() {
		global $wpdb;
		$post_type = self::get_slug();
		$years = $wpdb->get_col( "SELECT DISTINCT YEAR( post_date ) AS year FROM {$wpdb->posts} WHERE post_type = '{$post_type}' AND post_status = 'publish' ORDER BY post_date DESC" );
		if(!$years || !is_array($years)){
			$years = array();
		}
		return $years;
	}
}

new EASL_Newsletter_Config;