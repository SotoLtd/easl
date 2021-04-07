<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}


class EASL_Blog_CPT {
    
    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ), 0 );
        add_action( 'init', array( $this, 'register_categories' ), 0 );
        add_action( 'init', array( $this, 'register_tags' ), 0 );
        if ( is_admin() ) {
            //add_filter( 'wpex_metabox_array', array( $this, 'meta_array' ), 10, 2 );
        
        }
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
        register_taxonomy( 'blog_category', array( 'blog' ), array(
            
            'labels'            => array(
                'name'               => __( 'Blog Categories', 'total' ),
                'singular_name'      => __( 'Category', 'total' ),
                'menu_name'          => __( 'Categories', 'total' ),
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
    
    public function register_tags() {
        register_taxonomy( 'blog_tag', array( 'blog' ), array(
            'labels'            => array(
                'name'                       => __( 'Blog Tags', 'total' ),
                'singular_name'              => __( 'Tag', 'total' ),
                'menu_name'                  => __( 'Tags', 'total' ),
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
            'show_in_nav_menus' => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_tagcloud'     => false,
            'hierarchical'      => false,
            'rewrite'           => array( 'slug' => 'blog_tag', 'with_front' => false ),
            'query_var'         => true,
        ) );
    }
    
    /**
     *
     * @param array $array
     * @param WP_Post $post
     *
     * @return array
     */
    public static function meta_array( $array, $post ) {
       
        return $array;
    }
    
    
}

new EASL_Blog_CPT();