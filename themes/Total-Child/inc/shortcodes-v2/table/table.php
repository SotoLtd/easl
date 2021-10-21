<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_VC_Table extends EASL_ShortCode {
    public static function get_items_dd() {
        $_dd   = array( __( 'No tables found', 'total-child' ) => '' );
        $items = get_posts( array(
            'post_type'      => 'easl_table',
            'post_status'    => 'publish',
            'posts_per_page' => - 1,
            'order'          => 'ASC',
            'orderby'        => 'title',
        ) );
        if ( ! $items ) {
            return $_dd;
        }
        $_dd = array( __( 'Select a table', 'total-child' ) => '' );
        foreach ( $items as $item ) {
            $_dd[ get_the_title( $item->ID ) ] = $item->ID;
        }
        
        return $_dd;
    }
}