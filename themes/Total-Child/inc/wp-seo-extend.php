<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * SEO supports for event subpages
 */
class EASL_WP_SEO_For_Events_Subpages {
    private static $_instance;
    private $subpage_post;
    private $post_type = 'event_subpage';
    private $meta;
    private $permalink;
    
    /**
     * @return EASL_WP_SEO_For_Events_Subpages
     */
    public static function get_instance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    }
    
    /**
     * private constructor
     */
    private function __construct() {
        add_action( 'wpseo_head', [ $this, 'init' ], - 10000 );
        add_filter( 'wpseo_accessible_post_types', [ $this, 'add_support' ] );
        
    }
    
    /**
     * Init the class only for subpages
     */
    public function init() {
        $this->subpage_post = easl_get_the_event_subpage_post();
        if ( $this->subpage_post ) {
            $this->meta      = YoastSEO()->meta->for_post( $this->subpage_post->ID );
            $this->permalink = $this->current_subpage_url();
            $this->register_presenters_filters();
        }
    }
    
    /**
     * Register essential hooks for meta tags
     */
    public function register_presenters_filters() {
        add_filter( 'wpseo_title', [ $this, 'wpseo_title' ], 20, 2 );
        add_filter( 'wpseo_metadesc', [ $this, 'wpseo_metadesc' ], 20, 2 );
        add_filter( 'wpseo_canonical', [ $this, 'wpseo_canonical' ], 20, 2 );
        
        add_filter( 'wpseo_opengraph_url', [ $this, 'wpseo_opengraph_url' ], 20, 2 );
        add_filter( 'wpseo_opengraph_title', [ $this, 'wpseo_opengraph_title' ], 20, 2 );
        add_filter( 'wpseo_opengraph_desc', [ $this, 'wpseo_opengraph_desc' ], 20, 2 );
        add_filter( 'wpseo_opengraph_image', [ $this, 'wpseo_opengraph_image' ], 20, 2 );
        
        add_filter( 'wpseo_twitter_title', [ $this, 'wpseo_twitter_title' ], 20, 2 );
        add_filter( 'wpseo_twitter_description', [ $this, 'wpseo_twitter_description' ], 20, 2 );
        add_filter( 'wpseo_twitter_image', [ $this, 'wpseo_twitter_image' ], 20, 2 );
        
        add_filter( 'wpseo_schema_webpage', [ $this, 'schema_webpage' ], 20, 2 );
        add_filter( 'wpseo_schema_breadcrumb', [ $this, 'schema_breadcrumb' ], 20, 2 );
        
        add_filter( 'oembed_request_post_id', [ $this, 'get_subpage_id' ], 100, 1 );
        add_filter( 'oembed_discovery_links', [ $this, 'oembed_request_links' ], 12 );
    }
    
    public function wpseo_title( $title, $presentation ) {
        if ( ! $this->meta->title ) {
            return $title;
        }
        
        return $this->meta->title;
    }
    
    public function wpseo_metadesc( $meta_description, $presentation ) {
        if ( ! $this->meta->meta_description ) {
            return $meta_description;
        }
        
        return $this->meta->meta_description;
    }
    
    function wpseo_canonical( $canonical, $presentation ) {
        return $this->permalink;
    }
    
    
    public function wpseo_opengraph_url( $canonical, $obj ) {
        return $this->permalink;
    }
    
    public function wpseo_opengraph_title( $open_graph_title, $presentation ) {
        if ( ! $this->meta->open_graph_title ) {
            return $open_graph_title;
        }
        
        return $this->meta->open_graph_title;
    }
    
    public function wpseo_opengraph_desc( $open_graph_description, $presentation ) {
        if ( ! $this->meta->open_graph_description ) {
            return $open_graph_description;
        }
        
        return $this->meta->open_graph_description;
    }
    
    public function wpseo_opengraph_image( $open_graph_image, $presentation ) {
        $image_url = $this->get_opengraph_image_url();
        if ( ! $image_url ) {
            return $open_graph_image;
        }
        
        return $image_url;
    }
    
    public function get_opengraph_image_url() {
        if ( ! $this->meta->open_graph_images ) {
            return '';
        }
        $image = array_values( $this->meta->open_graph_images );
        if ( ! $image ) {
            return '';
        }
        
        return $image[0]['url'];
    }
    
    public function wpseo_twitter_title( $twitter_title, $presentation ) {
        if ( ! $this->meta->twitter_title ) {
            return $twitter_title;
        }
        
        return $this->meta->twitter_title;
    }
    
    public function wpseo_twitter_description( $twitter_description, $presentation ) {
        if ( ! $this->meta->open_graph_description ) {
            return $twitter_description;
        }
        
        return $this->meta->twitter_description;
    }
    
    public function wpseo_twitter_image( $twitter_image, $presentation ) {
        if ( $this->meta->twitter_image ) {
            $twitter_image = $this->meta->twitter_image;
        } elseif ( $og_image_url = $this->get_opengraph_image_url() ) {
            $twitter_image = $og_image_url;
        }
        
        return $twitter_image;
    }
    
    public function add_support( $post_types ) {
        if ( ! in_array( $this, $post_types ) ) {
            $post_types[] = EASL_Event_Subpage_Config::get_slug();
        }
        
        return $post_types;
    }
    
    public function schema_webpage( $graph_piece, $context ) {
        if ( isset( $graph_piece['@id'] ) ) {
            $graph_piece['@id'] = $this->permalink . '#webpage';
        }
        if ( isset( $graph_piece['url'] ) ) {
            $graph_piece['url'] = $this->permalink;
        }
        if ( isset( $graph_piece['breadcrumb']['@id'] ) ) {
            $graph_piece['breadcrumb']['@id'] = $this->permalink . '#breadcrumb';
        }
        if ( isset( $graph_piece['potentialAction'][0]['target'] ) ) {
            $graph_piece['potentialAction'][0]['target'][0] = $this->permalink;
        }
        
        return $graph_piece;
    }
    
    public function schema_breadcrumb( $graph_piece, $context ) {
        if ( isset( $graph_piece['@id'] ) ) {
            $graph_piece['@id'] = $this->permalink . '#breadcrumb';
        }
        if ( isset( $graph_piece['itemListElement'] ) ) {
            foreach ( $graph_piece['itemListElement'] as $key => $item ) {
                if ( $item['position'] == 2 ) {
                    $graph_piece['itemListElement'][ $key ]['item']['@id'] = $this->permalink;
                    $graph_piece['itemListElement'][ $key ]['item']['url'] = $this->permalink;
                }
                
            }
        }
        
        return $graph_piece;
    }
    
    public function oembed_request_links( $output ) {
        $output = '<link rel="alternate" type="application/json+oembed" href="' . esc_url( get_oembed_endpoint_url( $this->permalink ) ) . '" />' . "\n";
        
        if ( class_exists( 'SimpleXMLElement' ) ) {
            $output .= '<link rel="alternate" type="text/xml+oembed" href="' . esc_url( get_oembed_endpoint_url( $this->permalink, 'xml' ) ) . '" />' . "\n";
        }
        
        return $output;
    }
    
    public function get_subpage_id( $post_id ) {
        return $this->subpage_post->ID;
    }
    
    public function current_subpage_url() {
        $subpage_slug   = get_query_var( 'easl_event_subpage' );
        $subpage_slug_2 = get_query_var( 'easl_event_subpage2' );
        $base_url       = untrailingslashit( get_permalink( get_queried_object_id() ) );
        if ( $subpage_slug ) {
            $base_url .= '/' . $subpage_slug;
        }
        if ( $subpage_slug_2 ) {
            $base_url .= '/' . $subpage_slug_2;
        }
        
        return trailingslashit( $base_url );
    }
}

EASL_WP_SEO_For_Events_Subpages::get_instance();