<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_VC_Scientific_Publication extends EASL_ShortCode {
    public function get_sanitized_int_aray( $input_array ) {
        if ( ! is_array( $input_array ) ) {
            return array();
        }
        $return = array();
        foreach ( $input_array as $item ) {
            $s_item = absint( $item );
            if ( $s_item ) {
                $return[] = $s_item;
            }
        }
        
        return $return;
    }
    
    public function get_years( $cateogry = array(), $topics = array() ) {
        global $wpdb;
        $cateogry = $this->get_sanitized_int_aray( $cateogry );
        $topics   = $this->get_sanitized_int_aray( $topics );
        
        $cat_join = $cat_where = $topic_join = $topic_where = '';
        if ( count( $cateogry ) > 0 ) {
            $cat_join  = " LEFT JOIN {$wpdb->term_relationships} ON ({$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id)";
            $cat_where = " {$wpdb->term_relationships}.term_taxonomy_id IN (" . implode( ',', $cateogry ) . ")";
        }
        if ( count( $topics ) > 0 ) {
            $topic_join  = " LEFT JOIN {$wpdb->term_relationships} AS tt_topic  ON ({$wpdb->posts}.ID = tt_topic.object_id)";
            $topic_where = " tt_topic.term_taxonomy_id IN (" . implode( ',', $topics ) . ") ";
        }
        $sql = "SELECT DISTINCT SUBSTRING({$wpdb->postmeta}.meta_value, 1, 4) AS year FROM {$wpdb->posts}";
        $sql .= "{$cat_join}{$topic_join}";
        $sql .= " INNER JOIN {$wpdb->postmeta} ON ( {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ) ";
        $sql .= " WHERE (1=1)";
        if ( $cat_where && $topic_where ) {
            $sql .= " AND ({$cat_where} AND {$topic_where})";
        }
        $sql .= " AND ({$wpdb->postmeta}.meta_key = 'publication_raw_date') ";
        $sql .= " AND ({$wpdb->posts}.post_type = 'publication') AND ({$wpdb->posts}.post_status = 'publish') ORDER BY {$wpdb->postmeta}.meta_value DESC";
        
        $years = $wpdb->get_col( $sql );
        
        if ( ! $years || ! is_array( $years ) ) {
            $years = array();
        }
        
        return $years;
    }
    
    /**
     * Suggest Publication Categories for autocomplete
     *
     * @since 2.1.0
     */
    static public function vcex_suggest_publication_categories( $search_string ) {
        $publication_categories = array();
        $get_terms              = get_terms(
            'publication_category', array(
            'hide_empty' => false,
            'search'     => $search_string,
        ) );
        
        if ( $get_terms ) {
            foreach ( $get_terms as $term ) {
                if ( $term->parent ) {
                    $parent = get_term( $term->parent, 'publication_category' );
                    $label  = $term->name . ' (' . $parent->name . ')';
                } else {
                    $label = $term->name;
                }
                $publication_categories[] = array(
                    'label' => $label,
                    'value' => $term->term_id,
                );
            }
        }
        
        return $publication_categories;
    }
    
    /**
     * Renders Publication Categories for autocomplete
     *
     * @since 2.1.0
     */
    static public function vcex_render_publication_categories( $data ) {
        $value = $data['value'];
        $term  = get_term_by( 'term_id', intval( $value ), 'publication_category' );
        if ( is_object( $term ) ) {
            if ( $term->parent ) {
                $parent = get_term( $term->parent, 'publication_category' );
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
    
    public function get_related_links_data( $rlinks_param ) {
        $related_links_data = array();
        if ( strlen( $rlinks_param ) > 0 ) {
            $related_links_data = vc_param_group_parse_atts( $rlinks_param );
        }
        if ( empty( $related_links_data ) ) {
            $related_links_data = array();
        }
        $parsed_links_data = array();
        foreach ( $related_links_data as $link ) {
            if ( empty( $link['rlink'] ) ) {
                continue;
            }
            $p_link = $this->parse_url( $link['rlink'] );
            if ( strlen( $p_link['url'] ) > 0 ) {
                $parsed_links_data[] = $p_link;
            }
        }
        
        return $parsed_links_data;
    }
    
    public function parse_url( $link ) {
        //parse link
        $link = ( '||' === $link ) ? '' : $link;
        
        return vc_build_link( $link );
    }
    
}

// Admin filters
if ( is_admin() ) {
    
    // Get autocomplete suggestion
    add_filter( 'vc_autocomplete_easl_scientific_publication_include_categories_callback', array(
        'EASL_VC_Scientific_Publication',
        'vcex_suggest_publication_categories'
    ), 10, 1 );
    add_filter( 'vc_autocomplete_easl_scientific_publication_exclude_categories_callback', array(
        'EASL_VC_Scientific_Publication',
        'vcex_suggest_publication_categories'
    ), 10, 1 );
    add_filter( 'vc_autocomplete_easl_scientific_publication_filter_active_category_callback', array(
        'EASL_VC_Scientific_Publication',
        'vcex_suggest_publication_categories'
    ), 10, 1 );
    
    // Render autocomplete suggestions
    add_filter( 'vc_autocomplete_easl_scientific_publication_include_categories_render', array(
        'EASL_VC_Scientific_Publication',
        'vcex_render_publication_categories'
    ), 10, 1 );
    add_filter( 'vc_autocomplete_easl_scientific_publication_exclude_categories_render', array(
        'EASL_VC_Scientific_Publication',
        'vcex_render_publication_categories'
    ), 10, 1 );
    add_filter( 'vc_autocomplete_easl_scientific_publication_filter_active_category_render', array(
        'EASL_VC_Scientific_Publication',
        'vcex_render_publication_categories'
    ), 10, 1 );
}