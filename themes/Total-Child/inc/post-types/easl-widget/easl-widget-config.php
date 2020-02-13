<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_Widget_Config {

	protected static $slugs = array(
		'type' => 'easl_widget',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		add_action( 'init', array( 'EASL_Widget_Config', 'register_post_type' ), 0 );
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
				'name' => __( 'Event Widget', 'total-child' ),
				'singular_name' => __( 'Event Widget', 'total-child' ),
				'add_new' => __( 'Add New', 'total-child' ),
				'add_new_item' => __( 'Add New Event Widget', 'total-child' ),
				'edit_item' => __( 'Edit Event Widget', 'total-child' ),
				'new_item' => __( 'Add New Event Widget', 'total-child' ),
				'view_item' => __( 'View Event Widget', 'total-child' ),
				'search_items' => __( 'Search Event Widgets', 'total-child' ),
				'not_found' => __( 'No Event Widgets Found', 'total-child' ),
				'not_found_in_trash' => __( 'No Event Widgets Found In Trash', 'total-child' )
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
	public static function get_widgets() {
		$widgets_dd = array();
		$widgets = get_posts( array(
			'post_type'      => self::get_slug(),
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'orderby' => 'title',
		));
		if(!$widgets) {
			return array();
		}
		foreach ($widgets as $widget) {
			$widgets_dd[] = array(
				'label' => get_the_title($widget->ID),
				'value' => $widget->post_name,
				'id' => $widget->ID
			);
		}
		return $widgets_dd;
	}
}

new EASL_Widget_Config();