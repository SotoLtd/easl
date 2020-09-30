<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_VC_Events_Key_Dates extends WPBakeryShortCode {


    /**
     * Suggest Staff Members for autocomplete.
     */
    static function suggest_events( $search_string ) {
        $entries = array();
        $_ids    = get_posts( array(
            'posts_per_page' => - 1,
            'post_type'      => 'event',
            's'              => $search_string,
            'fields'         => 'ids',
            'post_status'    => 'published',
        ) );
        if ( ! empty( $_ids ) ) {
            foreach ( $_ids as $id ) {
                $entries[] = array(
                    'label' => get_the_title( $id ),
                    'value' => $id,
                );
            }
        }
        wp_reset_postdata(); // is it really needed?

        return $entries;
    }

    /**
     * Suggest events for autocomplete.
     */
    static function vcex_render_events( $data ) {
        return array(
            'label' => get_the_title( $data['value'] ),
            'value' => $data['value'],
        );
    }
}


if ( is_admin() ) {
    // Get autocomplete suggestion
    add_filter( 'vc_autocomplete_easl_events_key_dates_event_id_callback', array(
        'EASL_VC_Events_Key_Dates',
        'suggest_events'
    ), 10, 1 );
    // Render autocomplete suggestions
    add_filter( 'vc_autocomplete_easl_events_key_dates_event_id_render', array(
        'EASL_VC_Events_Key_Dates',
        'vcex_render_events'
    ), 10, 1 );
};

vc_lean_map( 'easl_events_key_dates', null, get_theme_file_path( 'inc/shortcodes/easl-events-key-dates/map.php' ) );

