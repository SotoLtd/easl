<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * Studio Episode Post Type Configuration file
 *
 * @package    EASL Website
 * @subpackage Studio Episodes Functions
 */
class EASL_Studio_Episode_Config {
    protected $type = 'easl_studio_episode';
    protected $slug = 'easl-studio-episode';
    
    /**
     * Get thing started
     */
    public function __construct() {
        add_action( 'init', array( $this, 'register' ), 0 );
        add_filter( 'acf/load_field/name=episode_season', [ $this, 'acf_episode_season_default' ] );
        add_filter( 'acf/load_field/name=episode_number', [ $this, 'acf_episode_number_default' ] );
        add_action( 'acf/input/admin_enqueue_scripts', [ $this, 'acf_register_episode_scripts' ] );
        
        add_action( 'wp_ajax_es_get_episode_number', [ $this, 'ajax_get_episode_auto_number' ] );
        
        add_filter( 'wpseo_opengraph_image', [ $this, 'wpseo_opengraph_image' ] );
        add_filter( 'wpseo_twitter_image', [ $this, 'wpseo_twitter_image' ] );
    }
    
    /**
     * Register post type.
     *
     * @since 2.0.0
     */
    public function register() {
        register_post_type( $this->type, array(
            'labels'          => array(
                'name'               => __( 'EASL Studio Episodes', 'total' ),
                'menu_name'          => __( 'EASL Studio', 'total' ),
                'singular_name'      => __( 'Episode', 'total' ),
                'add_new'            => __( 'Add New Episode', 'total' ),
                'add_new_item'       => __( 'Add New Episode', 'total' ),
                'edit_item'          => __( 'Edit Episode', 'total' ),
                'new_item'           => __( 'Add New Episode', 'total' ),
                'view_item'          => __( 'View Episode', 'total' ),
                'search_items'       => __( 'Search Episodes', 'total' ),
                'not_found'          => __( 'No Episodes Found', 'total' ),
                'not_found_in_trash' => __( 'No Episodes Found In Trash', 'total' )
            ),
            'public'          => true,
            'capability_type' => 'post',
            'has_archive'     => false,
            'menu_icon'       => 'dashicons-video-alt',
            'menu_position'   => 20,
            'rewrite'         => array( 'slug' => $this->slug, 'with_front' => false ),
            'supports'        => array(
                'title',
                'editor',
                'author',
            ),
        ) );
    }
    
    function acf_episode_season_default( $field ) {
        $field['default_value'] = date( 'Y' );
        
        return $field;
    }
    
    function acf_episode_number_default( $field ) {
        $season                 = date( 'Y' );
        $field['default_value'] = $this->get_episode_auto_number( $season );
        
        return $field;
    }
    
    function ajax_get_episode_auto_number() {
        $season = '';
        if ( ! empty( $_POST['season'] ) ) {
            $season = $_POST['season'];
        }
        if ( ! $season ) {
            wp_send_json( [
                'Status'        => 'Failed',
                'EpisodeNumber' => '',
            ] );
        }
        wp_send_json( [
            'Status'        => 'OK',
            'EpisodeNumber' => $this->get_episode_auto_number( $season ),
        ] );
    }
    
    /**
     * @param string $season
     *
     * @return int
     */
    function get_episode_auto_number( $season = '' ) {
        /**
         * @var wpdb $wpdb
         */
        global $wpdb;
        if ( ! $season ) {
            $season = date( 'Y' );
        }
        $sql            = "SELECT epn.meta_value FROM {$wpdb->postmeta} AS epn";
        $sql            .= $wpdb->prepare( " INNER JOIN {$wpdb->postmeta} AS eps ON (epn.post_id = eps.post_id) AND (eps.meta_key = 'episode_season') AND (eps.meta_value = %d)", $season );
        $sql            .= " WHERE (1 = 1) AND epn.meta_key='episode_number'";
        $sql            .= " ORDER BY epn.meta_value DESC LIMIT 0, 1";
        $episode_number = $wpdb->get_var( $sql );
        if ( ! $episode_number ) {
            return 1;
        }
        $episode_number ++;
        
        return $episode_number;
    }
    
    function acf_register_episode_scripts() {
        wp_enqueue_script( 'acf-episode-script', get_stylesheet_directory_uri() . '/assets/js/admin/acf-episode-script.js', array( 'jquery' ), time(), true );
    }
    
    public function wpseo_opengraph_image( $image_url ) {
        if ( ! is_singular( $this->type ) ) {
            return $image_url;
        }
        $post_id = get_queried_object_id();
        if (
            get_post_meta( $post_id, '_yoast_wpseo_opengraph-image', true ) ||
            get_post_meta( $post_id, '_yoast_wpseo_twitter-image', true )
        ) {
            return $image_url;
        }
        
        $poster_image_id = get_field( 'episode_poster', $post_id );
        if ( $poster_image_id ) {
            $poster_image_src = wp_get_attachment_image_url( $poster_image_id, 'medium_large' );
        }
        if ( ! $poster_image_src ) {
            return $image_url;
        }
        
        return $poster_image_src;
    }
    
    public function wpseo_twitter_image( $image_url ) {
        if ( ! is_singular( $this->type ) ) {
            return $image_url;
        }
        $post_id = get_queried_object_id();
        if (
            get_post_meta( $post_id, '_yoast_wpseo_opengraph-image', true ) ||
            get_post_meta( $post_id, '_yoast_wpseo_twitter-image', true )
        ) {
            return $image_url;
        }
        
        $poster_image_id = get_field( 'episode_poster', $post_id );
        if ( $poster_image_id ) {
            $poster_image_src = wp_get_attachment_image_url( $poster_image_id, 'medium_large' );
        }
        
        if ( ! $poster_image_src ) {
            return $image_url;
        }
        
        return $poster_image_src;
    }
}

new EASL_Studio_Episode_Config;