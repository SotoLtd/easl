<?php
/**
 * Staff Post Type Configuration file
 *
 * @package Total WordPress Theme
 * @subpackage Staff Functions
 * @version 4.6.5
 */

// The class
class WPEX_Associations_Config {

    /**
     * Get things started.
     *
     * @since 2.0.0
     */
    public function __construct() {
        add_action( 'init', array( 'WPEX_Associations_Config', 'register_post_type' ), 0 );
    }

    public static function register_post_type() {

        // Declare args and apply filters
        $args = apply_filters( 'wpex_associations_args', array(

            'labels' => array(
                'name' => __( 'National Associations', 'total' ),
                'singular_name' => __( 'National Association', 'total' ),
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
            'supports' => array(
                'title',
                'excerpt',
                'thumbnail',
                'revisions',
                'author',
                'page-attributes',
            ),
            'capability_type' => 'post',
            'has_archive' => false,
            'rewrite' => false,
            'menu_icon' => 'dashicons-groups',
            'menu_position' => 20,
        ) );

        // Register the post type
        register_post_type( 'associations', $args );
    }
}
new WPEX_Associations_Config;