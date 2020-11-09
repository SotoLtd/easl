<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_Event_Subpage_Config {

	protected static $slugs = array(
		'type' => 'event_subpage',
		'group' => 'event_subpage_group',
	);

	/**
	 * Get thing started
	 */
	public function __construct() {
		add_action( 'init', array( 'EASL_Event_Subpage_Config', 'register_post_type' ), 0 );
        add_action( 'init', array( 'EASL_Event_Subpage_Config', 'register_group' ), 0 );
	}

	/**
	 * Get post type slug
	 * @return string
	 */
	public static function get_slug() {
		return self::$slugs['type'];
	}

	/**
	 * Get post type slug
	 * @return string
	 */
	public static function get_group_slug() {
		return self::$slugs['group'];
	}

	/**
	 * Register post type.
	 */
	public static function register_post_type() {
		register_post_type( self::get_slug(), array(
			'labels'          => array(
				'name'               => __( 'Event Subpage', 'total-child' ),
				'singular_name'      => __( 'Event Subpage', 'total-child' ),
				'add_new'            => __( 'Add New', 'total-child' ),
				'add_new_item'       => __( 'Add New Event Subpage', 'total-child' ),
				'edit_item'          => __( 'Edit Event Subpage', 'total-child' ),
				'new_item'           => __( 'Add New Event Subpage', 'total-child' ),
				'view_item'          => __( 'View Event Subpage', 'total-child' ),
				'search_items'       => __( 'Search Event Subpage', 'total-child' ),
				'not_found'          => __( 'No Event Subpage Found', 'total-child' ),
				'not_found_in_trash' => __( 'No Event Subpage Found In Trash', 'total-child' )
			),
			'public'          => true,
			'hierarchical' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_ui'         => true,
			'show_in_menu'         => 'edit.php?post_type=event',
			'show_in_nav_menus'         => false,
			'show_in_admin_bar'         => false,
			'show_in_rest'         => false,
			'menu_position'   => 25,
			'capability_type' => 'post',
			'has_archive'     => false,
			'rewrite'         => false,
			'supports'        => array(
				'title',
				'editor',
			),
		) );
	}
    
    public static function register_group() {
        
        // Define args and apply filters for child theming
        $args = array(
            
            'labels' => array(
                'name' => __( 'Event Subpage Group', 'total' ),
                'singular_name' => __( 'Event subpage group', 'total' ),
                'add_new' => __( 'Add New', 'total' ),
                'add_new_item' => __( 'Add New Item', 'total' ),
                'edit_item' => __( 'Edit Item', 'total' ),
                'new_item' => __( 'Add New Item', 'total' ),
                'view_item' => __( 'View Item', 'total' ),
                'search_items' => __( 'Search Items', 'total' ),
                'not_found' => __( 'No Items Found', 'total' ),
                'not_found_in_trash' => __( 'No Items Found In Trash', 'total' )
            ),
            'public' => false,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_tagcloud' => false,
            'hierarchical' => true,
            'rewrite' => false,
            'query_var' => false
        );
        
        // Register the staff category taxonomy
        register_taxonomy( self::get_group_slug(), array( self::get_slug() ), $args );
        
    }
    
}

new EASL_Event_Subpage_Config();