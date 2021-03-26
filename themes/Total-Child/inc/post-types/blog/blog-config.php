<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


class EASL_Blog_CPT {
    
    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ), 0 );
        add_action( 'init', array( $this, 'register_categories' ), 0 );
    }
    
    public function register_post_type() {
        register_post_type( 'blog', array(
            'labels'          => array(
                'name'               => __( 'Blogs', 'total' ),
                'singular_name'      => __( 'Blog', 'total' ),
                'add_new'            => __( 'Add New', 'total' ),
                'add_new_item'       => __( 'Add New Blog', 'total' ),
                'edit_item'          => __( 'Edit Blog', 'total' ),
                'new_item'           => __( 'Add New Blog', 'total' ),
                'view_item'          => __( 'View Blog', 'total' ),
                'search_items'       => __( 'Search Blogs', 'total' ),
                'not_found'          => __( 'No Blogs Found', 'total' ),
                'not_found_in_trash' => __( 'No Blogs Found In Trash', 'total' ),
                'all_items'          => __( 'All Blogs', 'total' ),
            ),
            'public'          => true,
            'capability_type' => 'post',
            'has_archive'     => false,
            'menu_icon'       => 'dashicons-calendar-alt',
            'menu_position'   => 20,
            'rewrite'         => array( 'slug' => 'blog', 'with_front' => false ),
            'supports'        => array(
                'title',
                'editor',
                'excerpt',
                'thumbnail',
                'comments',
                'revisions',
                'author',
                'page-attributes',
            ),
        ) );
    }
    
    public function register_categories() {
        
        // Register the staff category taxonomy
        register_taxonomy( 'blog_category', array( 'blog' ), array(
            
            'labels'            => array(
                'name'               => __( 'Blog Categories', 'total' ),
                'singular_name'      => __( 'Category', 'total' ),
                'add_new'            => __( 'Add New', 'total' ),
                'add_new_item'       => __( 'Add New Category', 'total' ),
                'edit_item'          => __( 'Edit Category', 'total' ),
                'new_item'           => __( 'Add New Category', 'total' ),
                'view_item'          => __( 'View Categories', 'total' ),
                'search_items'       => __( 'Search Categories', 'total' ),
                'not_found'          => __( 'No Categories Found', 'total' ),
                'not_found_in_trash' => __( 'No Categories Found In Trash', 'total' )
            ),
            'public'            => true,
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_tagcloud'     => true,
            'hierarchical'      => true,
            'rewrite'           => array( 'slug' => 'blog-category', 'with_front' => false ),
            'query_var'         => true
        ) );
        
    }
    
    
}

new EASL_Blog_CPT();