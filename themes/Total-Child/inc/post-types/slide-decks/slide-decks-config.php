<?php

class Slide_Decks_Config {

    protected static $slugs = array(
        'slide_decks' => 'slide_decks',
	    'category'  => 'slide_decks_category',
	    'topic' => 'slide_decks_topic'
    );

    /**
     * Get thing started
     */
    public function __construct() {
        add_action( 'init', array( 'Slide_Decks_Config', 'register_post_type' ), 0 );
        add_action( 'init', array( 'Slide_Decks_Config', 'register_categories' ), 0 );
        add_action( 'init', array( 'Slide_Decks_Config', 'register_topics' ), 0 );
        if ( is_admin() ) {
            // Add settings metabox to event
            add_filter( 'wpex_main_metaboxes_post_types', array( 'Slide_Decks_Config', 'meta_array' ), 20 );
            add_filter('wpex_term_meta_options', array('Slide_Decks_Config', 'topic_meta_array'), 10, 2);
        }
    }

    public static function get_slide_decks_slug(){
        return self::$slugs['slide_decks'];
    }

	public static function get_slug(){
		return self::$slugs['slide_decks'];
	}
	public static function get_category_slug(){
		return self::$slugs['category'];
	}

	public static function get_topic_slug(){
		return self::$slugs['topic'];
	}

    /**
     * Register post type.
     */
    public static function register_post_type() {
        register_post_type( self::get_slug(), array(
            'labels' => array(
                'name' => __( 'Slide Decks', 'total' ),
                'singular_name' => __( 'Slide Decks', 'total' ),
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
            'show_ui' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'menu_position' => 20,
            'rewrite' => false,
            'supports' => array(
                'title',
                'excerpt',
                'thumbnail',
                'author',
                'page-attributes',
            ),
        ) );
    }

    public static function register_categories() {

        // Define args and apply filters for child theming
        $args = apply_filters( 'wpex_taxonomy_slide_decks_category_args', array(

            'labels' => array(
                'name' => __( 'Slide Deck categories', 'total' ),
                'singular_name' => __( 'Slide Deck category', 'total' ),
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
            'rewrite' => false,
            'query_var' => false
        ) );

        // Register the staff category taxonomy
        register_taxonomy( self::get_category_slug(), array( self::get_slug() ), $args );

    }

    public static function register_topics() {

        // Define Event Type arguments
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
            'rewrite' => false,
            'query_var' => false,
        );

        register_taxonomy( self::get_topic_slug(), array( self::get_slug()  ), $args );
    }

    public static function meta_array( $types ) {
        $types[] = 'slide_decks';
        return $types;
    }

    public static function topic_meta_array($array){
        $array['easl_tax_color'] = array(
            'label'    => esc_html__( 'Color', 'total' ),
            'type'     => 'select',
            'choices'  => easl_taxonomoy_colors(),
        );
        return $array;
    }


}

new Slide_Decks_Config;