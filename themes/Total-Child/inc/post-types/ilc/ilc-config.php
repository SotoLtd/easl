<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_ILC_Config {

	protected static $slugs = array(
		'type' => 'ilc',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		add_action( 'init', array( 'EASL_ILC_Config', 'register_post_type' ), 0 );
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
				'name' => __( 'ILC', 'total-child' ),
				'singular_name' => __( 'ILC', 'total-child' ),
				'add_new' => __( 'Add New', 'total-child' ),
				'add_new_item' => __( 'Add New ILC', 'total-child' ),
				'edit_item' => __( 'Edit ILC', 'total-child' ),
				'new_item' => __( 'Add New ILC', 'total-child' ),
				'view_item' => __( 'View ILC', 'total-child' ),
				'search_items' => __( 'Search ILCs', 'total-child' ),
				'not_found' => __( 'No ILCs Found', 'total-child' ),
				'not_found_in_trash' => __( 'No ILCs Found In Trash', 'total-child' )
			),
			'public' => false,
			'show_ui' => true,
			'capability_type' => 'post',
			'has_archive' => false,
			'menu_position' => 25,
			'rewrite' => false,
			'supports' => array(
				'title'
			),
		) );
	}

	/**
	 * Get ilc dropdown data
	 * @return array
	 */
	public static function get_ilcs() {
		$ilcs_dd = array();
		$ilcs = get_posts( array(
			'post_type'      => EASL_ILC_Config::get_slug(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'order' => 'DESC',
			'orderby' => 'title',
		));
		if(!$ilcs) {
			return array();
		}
		foreach ($ilcs as $ilc) {
			$ilcs_dd[] = array(
				'label' => get_the_title($ilc->ID),
				'value' => $ilc->post_name,
				'id' => $ilc->ID
			);
		}
		return $ilcs_dd;
	}
}

new EASL_ILC_Config();