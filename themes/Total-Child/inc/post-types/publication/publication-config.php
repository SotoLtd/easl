<?php

class Publication_Config {

    protected static $slugs = array(
        'publication' => 'publication',
        'topic' => 'publication_topic',
    );

    /**
     * Get thing started
     */
    public function __construct() {
        add_action( 'init', array( 'Publication_Config', 'register_post_type' ), 0 );
        add_action( 'init', array( 'Publication_Config', 'register_categories' ), 0 );
        add_action( 'init', array( 'Publication_Config', 'register_topics' ), 0 );
        if ( is_admin() ) {
            // Add settings metabox to event
            add_filter( 'wpex_main_metaboxes_post_types', array( 'Publication_Config', 'meta_array' ), 20 );
        }
    }

    public static function get_publication_slug(){
        return self::$slugs['publication'];
    }

    public static function meta_array( $types ) {
        $types[] = 'publication';
        return $types;
    }

    public static function get_topic_slug(){
        return self::$slugs['topic'];
    }


    /**
     * Register post type.
     */
    public static function register_post_type() {
        register_post_type( 'publication', array(
            'labels' => array(
                'name' => 'Publications',
                'singular_name' => 'Publication',
                'add_new' => __( 'Add New', 'total' ),
                'add_new_item' => __( 'Add New Publication', 'total' ),
                'edit_item' => __( 'Edit Publication', 'total' ),
                'new_item' => __( 'Add New Publication', 'total' ),
                'view_item' => __( 'View Publication', 'total' ),
                'search_items' => __( 'Search Publications', 'total' ),
                'not_found' => __( 'No Publications Found', 'total' ),
                'not_found_in_trash' => __( 'No Publications Found In Trash', 'total' )
            ),
            'public' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'menu_icon' => 'dashicons-media-text',
            'menu_position' => 20,
            'rewrite' => array( 'slug' => 'publication', 'with_front' => false ),
            'supports' => array(
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

    public static function register_categories() {

        // Define args and apply filters for child theming
        $args = apply_filters( 'wpex_taxonomy_publication_category_args', array(

            'labels' => array(
                'name' => __( 'Publication categories', 'total' ),
                'singular_name' => __( 'Publication category', 'total' ),
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
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_tagcloud' => true,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => 'publication-category', 'with_front' => false ),
            'query_var' => true
        ) );

        // Register the staff category taxonomy
        register_taxonomy( 'publication_category', array( 'publication' ), $args );

    }

    public static function register_topics() {
        $args = array(
            'labels' => array(
                'name' => __( 'Topic', 'total' ),
                'singular_name' => __( 'Topic', 'total' ),
                'menu_name' => __( 'Topic', 'total' ),
                'search_items' => __( 'Search','total' ),
                'popular_items' => __( 'Popular', 'total' ),
                'all_items' => __( 'All', 'total' ),
                'parent_item' => __( 'Parent', 'total' ),
                'parent_item_colon' => __( 'Parent', 'total' ),
                'edit_item' => __( 'Edit', 'total' ),
                'update_item' => __( 'Update', 'total' ),
                'add_new_item' => __( 'Add New', 'total' ),
                'new_item_name' => __( 'New', 'total' ),
                'separate_items_with_commas' => __( 'Separate with commas', 'total' ),
                'add_or_remove_items' => __( 'Add or remove', 'total' ),
                'choose_from_most_used' => __( 'Choose from the most used', 'total' ),
            ),
            'public' => true,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_tagcloud' => false,
            'hierarchical' => true,
            'rewrite' => array( 'slug' => 'publication_topic', 'with_front' => false ),
            'query_var' => true,
        );

        register_taxonomy( self::get_topic_slug(), array( self::get_publication_slug() ), $args );
    }




}

new Publication_Config;