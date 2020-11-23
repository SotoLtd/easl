<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
add_filter( 'wpseo_accessible_post_types', 'easl_wpseo_accessible_post_types' );

add_action( 'wpseo_head', 'easl_wpseo_head_open', - 10000 );
add_action( 'wpseo_head', 'easl_wpseo_head_close', 100 );// TODO: may be set it to a possitive larger number

add_filter( 'oembed_request_post_id', 'easl_oembed_request_post_id', 100, 2 );
add_filter( 'oembed_discovery_links', 'easl_oembed_discovery_links', 12 );

function easl_oembed_request_post_id( $post_id, $request_url ) {
    if(get_post_type($post_id) != 'event') {
        return $post_id;
    }
    if ( preg_match('/event\/[^\/]+\/([^\/]+)\/?$/', $request_url, $matches) ) {
        $event_subpage = easl_get_event_subpage_by_slug($post_id, $matches[1]);
        if($event_subpage){
            return $event_subpage->ID;
        }
    }
    
    return $post_id;
}

function easl_oembed_discovery_links( $output ) {
    if ( ! easl_get_the_event_subpage() ) {
        return $output;
    }
    $output = '<link rel="alternate" type="application/json+oembed" href="' . esc_url( get_oembed_endpoint_url( easl_wpso_current_event_subpage_url() ) ) . '" />' . "\n";
    
    if ( class_exists( 'SimpleXMLElement' ) ) {
        $output .= '<link rel="alternate" type="text/xml+oembed" href="' . esc_url( get_oembed_endpoint_url( easl_wpso_current_event_subpage_url(), 'xml' ) ) . '" />' . "\n";
    }
    
    return $output;
}

function easl_wpseo_accessible_post_types( $post_types ) {
    if ( ! in_array( EASL_Event_Subpage_Config::get_slug(), $post_types ) ) {
        $post_types[] = EASL_Event_Subpage_Config::get_slug();
    }
    
    return $post_types;
}

function easl_wpseo_head_open() {
    global $post;
    $event_subpage_post = easl_get_the_event_subpage();
    if ( $event_subpage_post ) {
        $GLOBALS['easl_event_org_post'] = $post;
        $post                           = $event_subpage_post;
        //add_filter( 'wpseo_metadesc', 'easl_wpseo_metadesc', 20, 2 );
        //add_filter( 'wpseo_title', 'easl_wpseo_title', 20, 2 );
        //add_filter( 'wpseo_opengraph_title', 'easl_wpseo_opengraph_title', 20, 2 );
        add_filter( 'wpseo_canonical', 'easl_wpseo_canonical', 20, 2 );
        add_filter( 'wpseo_opengraph_url', 'easl_wpseo_opengraph_url', 20, 2 );
        add_filter( 'wpseo_schema_webpage', 'easl_wpseo_schema_webpage', 20, 2 );
        add_filter( 'wpseo_schema_breadcrumb', 'easl_wpseo_schema_breadcrumb', 20, 2 );
    }
}

function easl_wpseo_head_close() {
    global $post;
    if ( ! empty( $GLOBALS['easl_event_org_post'] ) ) {
        $post = $GLOBALS['easl_event_org_post'];
        unset( $GLOBALS['easl_event_org_post'] );
        //remove_filter( 'wpseo_canonical', 'easl_wpseo_canonical', 20 );
        //remove_filter( 'wpseo_schema_webpage', 'easl_wpseo_schema_graph_pieces', 20 );
        //remove_filter( 'wpseo_schema_webpage', 'easl_wpseo_schema_webpage', 20 );
        //remove_filter( 'wpseo_schema_breadcrumb', 'easl_wpseo_schema_breadcrumb', 20 );
    }
}

function easl_wpseo_metadesc( $meta_description, $presentation ) {
    $subpage_id               = easl_get_the_event_subpage_id();
    $subpage_meta_description = get_post_meta( $subpage_id, '_yoast_wpseo_metadesc', true );
    
    if ( $subpage_meta_description ) {
        return $subpage_meta_description;
    }
    
    return $meta_description;
}

function easl_wpseo_title( $title, $presentation ) {
    $subpage_post = easl_get_the_event_subpage();
    
    if ( $subpage_post ) {
        $subpage_title     = apply_filters( 'single_post_title', $subpage_post->post_title, $subpage_post );
        $subpage_seo_title = get_post_meta( $subpage_post->ID, '_yoast_wpseo_title', true );
        if ( $subpage_seo_title ) {
            $subpage_title = $subpage_seo_title;
        }
    }
    
    if ( $subpage_title ) {
        return $subpage_title;
    }
    
    return $title;
}

function easl_wpseo_canonical( $url, $obj ) {
    return easl_wpso_current_event_subpage_url();
}

function easl_wpseo_opengraph_title( $title, $presentation ) {
    $subpage_post = easl_get_the_event_subpage();
    
    if ( $subpage_post ) {
        $subpage_title    = apply_filters( 'single_post_title', $subpage_post->post_title, $subpage_post );
        $subpage_og_title = get_post_meta( $subpage_post->ID, '_yoast_wpseo_opengraph-title', true );
        if ( $subpage_og_title ) {
            $subpage_title = $subpage_og_title;
        }
    }
    
    if ( $subpage_title ) {
        return $subpage_title;
    }
    
    return $title;
}

function easl_wpseo_opengraph_url( $canonical, $obj ) {
    return easl_wpso_current_event_subpage_url();
}

function easl_wpso_current_event_subpage_url() {
    $subpage_slug = get_query_var( 'easl_event_subpage' );
    
    return trailingslashit( untrailingslashit( get_permalink( get_queried_object_id() ) ) . '/' . $subpage_slug );
}


function easl_wpseo_schema_webpage( $graph_piece, $context ) {
    $subpage_url = easl_wpso_current_event_subpage_url();
    if ( isset( $graph_piece['@id'] ) ) {
        $graph_piece['@id'] = $subpage_url . '#webpage';
    }
    if ( isset( $graph_piece['url'] ) ) {
        $graph_piece['url'] = $subpage_url;
    }
    if ( isset( $graph_piece['breadcrumb']['@id'] ) ) {
        $graph_piece['breadcrumb']['@id'] = $subpage_url . '#breadcrumb';
    }
    if ( isset( $graph_piece['potentialAction'][0]['target'] ) ) {
        $graph_piece['potentialAction'][0]['target'][0] = $subpage_url;
    }
    
    return $graph_piece;
}

function easl_wpseo_schema_breadcrumb( $graph_piece, $context ) {
    $subpage_url = easl_wpso_current_event_subpage_url();
    if ( isset( $graph_piece['@id'] ) ) {
        $graph_piece['@id'] = $subpage_url . '#breadcrumb';
    }
    if ( isset( $graph_piece['itemListElement'] ) ) {
        foreach ( $graph_piece['itemListElement'] as $key => $item ) {
            if ( $item['position'] == 2 ) {
                $graph_piece['itemListElement'][ $key ]['item']['@id'] = $subpage_url;
                $graph_piece['itemListElement'][ $key ]['item']['url'] = $subpage_url;
            }
            
        }
    }
    
    return $graph_piece;
}