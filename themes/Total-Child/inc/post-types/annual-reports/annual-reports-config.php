<?php

class Annual_Reports_Config {

    protected static $slugs = array(
        'annual_reports' => 'annual_reports',
    );

    /**
     * Get thing started
     */
    public function __construct() {
        add_action( 'init', array( 'Annual_Reports_Config', 'register_post_type' ), 0 );
    }

    public static function get_annual_reports_slug(){
        return self::$slugs['annual_reports'];
    }

    /**
     * Register post type.
     */
    public static function register_post_type() {
        register_post_type( self::get_annual_reports_slug(), array(
            'labels' => array(
                'name' => __( 'Annual Reports', 'total' ),
                'singular_name' => __( 'Annual Report', 'total' ),
                'add_new' => __( 'Add New', 'total' ),
                'add_new_item' => __( 'Add New Item', 'total' ),
                'edit_item' => __( 'Edit Item', 'total' ),
                'new_item' => __( 'Add New Item', 'total' ),
                'view_item' => __( 'View Item', 'total' ),
                'search_items' => __( 'Search Items', 'total' ),
                'not_found' => __( 'No Items Found', 'total' ),
                'not_found_in_trash' => __( 'No Items Found In Trash', 'total' )
            ),
            'public' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => false,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'menu_position' => 20,
            'rewrite' => false,
            'supports' => array(
                'title',
                'thumbnail',
                'author',
                'page-attributes',
            ),
        ) );
    }

}

new Annual_Reports_Config;