<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_VC_Popular_Blogs extends EASL_ShortCode {
    public function get_popular_blog_ids() {
        global $wpdb;
        $select          = "SELECT DISTINCT {$wpdb->posts}.ID, {$wpdb->posts}.comment_count * 2 + lc.meta_value * 3 + hc.meta_value AS rating  FROM {$wpdb->posts}";
        $join_love_count = "LEFT JOIN {$wpdb->postmeta} AS lc ON ({$wpdb->posts}.ID = lc.post_id) AND (lc.meta_key = 'easl_love_count')";
        $join_hit_count  = "LEFT JOIN {$wpdb->postmeta} AS hc ON ({$wpdb->posts}.ID = hc.post_id) AND (hc.meta_key = 'easl_hit_count')";
        $where           = "WHERE (1 = 1) AND ({$wpdb->posts}.post_type = 'blog') AND ({$wpdb->posts}.post_status = 'publish')";
        $order           = "ORDER BY rating DESC LIMIT 0, 30";
        
        $sql = "$select $join_hit_count $join_love_count $where $order";
        $result = $wpdb->get_col( $sql );
        if($result) {
            $result = array_map('absint', $result);
        }else{
            $result = array();
        }
        return $result;
    }
}