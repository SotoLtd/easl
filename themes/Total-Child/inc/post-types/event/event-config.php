<?php
/**
 * Event Post Type Configuration file
 *
 * @package EASL Website
 * @subpackage Event Functions
 */

class EASL_Event_Config {
	protected static $slugs = array(
		'event'  => 'event',
		'topic'  => 'event_topic',
		'type'   => 'event_type',
		'format' => 'event_format',
		//'key_dates' => 'key_dates',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		// Include helper functions first so we can use them in the class
		require_once EASL_INC_DIR . 'post-types/event/event-helpers.php';
		// Adds the event post type
		add_action( 'init', array( 'EASL_Event_Config', 'register_post_type' ), 0 );
		// Register event format
		add_action( 'init', array( 'EASL_Event_Config', 'register_event_format' ), 0 );
		// Register event type
		add_action( 'init', array( 'EASL_Event_Config', 'register_event_type' ), 0 );
		// Register event topics
		add_action( 'init', array( 'EASL_Event_Config', 'register_topics' ), 0 );
		// Register event key dates
		//add_action( 'init', array( 'EASL_Event_Config', 'register_key_dates' ), 0 );
		if ( is_admin() ) {
			// Add gallery metabox to event 
			add_filter( 'wpex_gallery_metabox_post_types', array( 'EASL_Event_Config', 'add_gallery_metabox' ), 20 );
			// Add meta fields for event
			add_filter( 'wpex_metabox_array', array( 'EASL_Event_Config', 'meta_array' ), 10, 2 );
			// Add meta fields for event Topic
			add_filter( 'wpex_term_meta_options', array( 'EASL_Event_Config', 'topic_meta_array' ), 10, 2 );

		}

	}

	public static function get_event_slug() {
		return self::$slugs['event'];
	}

	public static function get_topic_slug() {
		return self::$slugs['topic'];
	}

	public static function get_format_slug() {
		return self::$slugs['format'];
	}

	public static function get_meeting_type_slug() {
		return self::$slugs['type'];
	}

//    public static function get_key_dates_slug(){
//        return self::$slugs['key_dates'];
//    }
	/**
	 * Register post type.
	 *
	 * @since 2.0.0
	 */
	public static function register_post_type() {
		register_post_type( self::get_event_slug(), array(
			'labels'          => array(
				'name'               => __( 'Events', 'total' ),
				'singular_name'      => __( 'Event', 'total' ),
				'add_new'            => __( 'Add New', 'total' ),
				'add_new_item'       => __( 'Add New Event', 'total' ),
				'edit_item'          => __( 'Edit Event', 'total' ),
				'new_item'           => __( 'Add New Event', 'total' ),
				'view_item'          => __( 'View Event', 'total' ),
				'search_items'       => __( 'Search Events', 'total' ),
				'not_found'          => __( 'No Events Found', 'total' ),
				'not_found_in_trash' => __( 'No Events Found In Trash', 'total' )
			),
			'public'          => true,
			'capability_type' => 'post',
			'has_archive'     => false,
			'menu_icon'       => 'dashicons-calendar-alt',
			'menu_position'   => 20,
			'rewrite'         => array( 'slug' => self::get_event_slug(), 'with_front' => false ),
			'supports'        => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'comments',
				'custom-fields',
				'revisions',
				'author',
				'page-attributes',
			),
		) );
	}

	/**
	 * Register Portfolio tags.
	 *
	 * @since 2.0.0
	 */
	public static function register_event_type() {

		// Define Event Type arguments
		$args = array(
			'labels'            => array(
				'name'                       => __( 'Event Type', 'total' ),
				'singular_name'              => __( 'Event Type', 'total' ),
				'menu_name'                  => __( 'Event Type', 'total' ),
				'search_items'               => __( 'Search', 'total' ),
				'popular_items'              => __( 'Popular', 'total' ),
				'all_items'                  => __( 'All', 'total' ),
				'parent_item'                => __( 'Parent', 'total' ),
				'parent_item_colon'          => __( 'Parent', 'total' ),
				'edit_item'                  => __( 'Edit', 'total' ),
				'update_item'                => __( 'Update', 'total' ),
				'add_new_item'               => __( 'Add New', 'total' ),
				'new_item_name'              => __( 'New', 'total' ),
				'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
				'add_or_remove_items'        => __( 'Add or remove', 'total' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'total' ),
			),
			'public'            => true,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'event_type', 'with_front' => false ),
			'query_var'         => true,
		);

		register_taxonomy( self::get_meeting_type_slug(), array( self::get_event_slug() ), $args );
	}

	/**
	 * Register Portfolio tags.
	 *
	 * @since 2.0.0
	 */
	public static function register_topics() {

		// Define Event Type arguments
		$args = array(
			'labels'            => array(
				'name'                       => __( 'Topic', 'total' ),
				'singular_name'              => __( 'Topic', 'total' ),
				'menu_name'                  => __( 'Topic', 'total' ),
				'search_items'               => __( 'Search', 'total' ),
				'popular_items'              => __( 'Popular', 'total' ),
				'all_items'                  => __( 'All', 'total' ),
				'parent_item'                => __( 'Parent', 'total' ),
				'parent_item_colon'          => __( 'Parent', 'total' ),
				'edit_item'                  => __( 'Edit', 'total' ),
				'update_item'                => __( 'Update', 'total' ),
				'add_new_item'               => __( 'Add New', 'total' ),
				'new_item_name'              => __( 'New', 'total' ),
				'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
				'add_or_remove_items'        => __( 'Add or remove', 'total' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'total' ),
			),
			'public'            => true,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'event_topic', 'with_front' => false ),
			'query_var'         => true,
		);

		register_taxonomy( self::get_topic_slug(), array( self::get_event_slug() ), $args );
	}

	/**
	 * Register event format tags.
	 *
	 * @since 1.2.8
	 */
	public static function register_event_format() {

		// Define Event Format arguments
		$args = array(
			'labels'            => array(
				'name'                       => __( 'Format', 'total' ),
				'singular_name'              => __( 'Format', 'total' ),
				'menu_name'                  => __( 'Format', 'total' ),
				'search_items'               => __( 'Search', 'total' ),
				'popular_items'              => __( 'Popular', 'total' ),
				'all_items'                  => __( 'All', 'total' ),
				'parent_item'                => __( 'Parent', 'total' ),
				'parent_item_colon'          => __( 'Parent', 'total' ),
				'edit_item'                  => __( 'Edit', 'total' ),
				'update_item'                => __( 'Update', 'total' ),
				'add_new_item'               => __( 'Add New', 'total' ),
				'new_item_name'              => __( 'New', 'total' ),
				'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
				'add_or_remove_items'        => __( 'Add or remove', 'total' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'total' ),
			),
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_in_rest'      => false,
			'show_admin_column' => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'rewrite'           => false,
			'query_var'         => false,
		);

		register_taxonomy( self::get_format_slug(), array( self::get_event_slug() ), $args );
	}

//    public static function register_key_dates() {
//
//        // Define Event Type arguments
//        $args = array(
//            'labels' => array(
//                'name' => __( 'Key Dates', 'total' ),
//                'singular_name' => __( 'Key Date', 'total' ),
//                'menu_name' => __( 'Key Dates', 'total' ),
//                'search_items' => __( 'Search','total' ),
//                'popular_items' => __( 'Popular', 'total' ),
//                'all_items' => __( 'All', 'total' ),
//                'parent_item' => __( 'Parent', 'total' ),
//                'parent_item_colon' => __( 'Parent', 'total' ),
//                'edit_item' => __( 'Edit', 'total' ),
//                'update_item' => __( 'Update', 'total' ),
//                'add_new_item' => __( 'Add New', 'total' ),
//                'new_item_name' => __( 'New', 'total' ),
//                'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
//                'add_or_remove_items' => __( 'Add or remove', 'total' ),
//                'choose_from_most_used' => __( 'Choose from the most used', 'total' ),
//            ),
//            'public' => true,
//            'show_in_nav_menus' => false,
//            'show_ui' => true,
//            'show_admin_column' => true,
//            'show_tagcloud' => false,
//            'hierarchical' => true,
//            'rewrite' => array( 'slug' => 'key_dates', 'with_front' => false ),
//            'query_var' => true,
//        );
//
//        register_taxonomy( self::get_key_dates_slug(), array( self::get_event_slug() ), $args );
//    }

	/**
	 * Adds the portfolio post type to the gallery metabox post types array.
	 *
	 * @since 2.0.0
	 */
	public static function add_gallery_metabox( $types ) {
		$types[] = 'event';

		return $types;
	}

	/**
	 *
	 * @param type $array
	 * @param type $post
	 *
	 * @return type
	 */
	public static function meta_array( $array, $post ) {
		if ( ( ($post->post_type == 'event') || ( isset( $_POST['post_type'] ) && ( 'event' == $_POST['post_type'] ) ) ) && isset( $array['title']['settings']['post_title'] ) ) {
			unset( $array['title']['settings']['post_title'] );
		}
		$events_meta_arr = require EASL_INC_DIR . 'post-types/event/event-meta-options.php';
		if ( empty( $events_meta_arr ) ) {
			return $array;
		}

		return array_merge( $events_meta_arr, $array );

	}

	public static function topic_meta_array( $array ) {
		$array['easl_tax_color'] = array(
			'label'   => esc_html__( 'Color', 'total' ),
			'type'    => 'select',
			'choices' => easl_taxonomoy_colors(),
		);

		return $array;
	}
}

new EASL_Event_Config;