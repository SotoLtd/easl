<?php

class EASL_VC_Blog_List extends EASL_ShortCode {
    
    public function string_to_array( $value ) {
        
        // Return if value is empty
        if ( ! $value ) {
            return array();
        }
        
        // Return if already an array
        if ( is_array( $value ) ) {
            return $value;
        }
        
        // Define output array
        $array = array();
        
        // Clean up value
        $items = preg_split( '/\,[\s]*/', $value );
        
        // Create array
        foreach ( $items as $item ) {
            if ( strlen( $item ) > 0 ) {
                $array[] = $item;
            }
        }
        
        // Return array
        return $array;
    }
    
    public static function suggest_categories( $search_string ) {
        $blog_categories = array();
        $get_terms       = get_terms(
            'blog_category', array(
            'hide_empty' => false,
            'search'     => $search_string,
        ) );
        
        if ( $get_terms ) {
            foreach ( $get_terms as $term ) {
                if ( $term->parent ) {
                    $parent = get_term( $term->parent, 'blog_category' );
                    $label  = $term->name . ' (' . $parent->name . ')';
                } else {
                    $label = $term->name;
                }
                $blog_categories[] = array(
                    'label' => $label,
                    'value' => $term->term_id,
                );
            }
        }
        
        return $blog_categories;
    }
    
    public static function render_categories( $data ) {
        $value = $data['value'];
        $term  = get_term_by( 'term_id', intval( $value ), 'blog_category' );
        if ( is_object( $term ) ) {
            if ( $term->parent ) {
                $parent = get_term( $term->parent, 'blog_category' );
                $label  = $term->name . ' (' . $parent->name . ')';
            } else {
                $label = $term->name;
            }
            
            return array(
                'label' => $label,
                'value' => $value,
            );
        }
        
        return $data;
    }
}

if ( is_admin() ) {
    // Get autocomplete suggestion
    add_filter( 'vc_autocomplete_easl_blog_list_include_categories_callback', array(
        'EASL_VC_Blog_List',
        'suggest_categories'
    ), 10, 1 );
    // Render autocomplete suggestions
    add_filter( 'vc_autocomplete_easl_blog_list_include_categories_render', array(
        'EASL_VC_Blog_List',
        'render_categories'
    ), 10, 1 );
    
    // Get autocomplete suggestion
    add_filter( 'vc_autocomplete_easl_blog_list_exclude_categories_callback', array(
        'EASL_VC_Blog_List',
        'suggest_categories'
    ), 10, 1 );
    // Render autocomplete suggestions
    add_filter( 'vc_autocomplete_easl_blog_list_exclude_categories_render', array(
        'EASL_VC_Blog_List',
        'render_categories'
    ), 10, 1 );
};
