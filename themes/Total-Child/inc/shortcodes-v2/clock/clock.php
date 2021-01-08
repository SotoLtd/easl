<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_VC_EASL_Clock extends EASL_ShortCode {
    public static $clock_version = '1.1';
    private static $clock_count = 0;
    public static function get_clock_count() {
        return self::$clock_count;
    }
    public static function updateClockCount() {
        self::$clock_count++;
    }
    public static function enqueueClockAssets() {
        
        wp_enqueue_style( 'easl-clock', get_stylesheet_directory_uri() . '/assets/css/easl-clock.css', array(), self::$clock_version );
        wp_enqueue_script( 'jquery_countdown_plugin', get_stylesheet_directory_uri() . '/assets/lib/jquery-countdown/js/jquery.plugin.min.js', array(
            'jquery',
        ), '2.1.0' );
        wp_enqueue_script( 'jquery_countdown', get_stylesheet_directory_uri() . '/assets/lib/jquery-countdown/js/jquery.countdown.min.js', array(
            'jquery',
            'jquery_countdown_plugin'
        ), '2.1.0' );
        wp_enqueue_script( 'easl-clock', get_stylesheet_directory_uri() . '/assets/js/easl-clock.js', array(
            'jquery',
            'jquery_countdown'
        ), self::$clock_version );
    }
    
    public static function get_date_time_parts( $date_time_str ) {
        $date_time_str = trim( $date_time_str );
        if ( ! $date_time_str ) {
            return false;
        }
        $date_time_parts = explode( ' ', $date_time_str );
        if ( ! $date_time_parts || count( $date_time_parts ) !== 2 ) {
            return false;
        }
        $date_parts = explode( '-', $date_time_parts[0] );
        if ( ! $date_parts || count( $date_parts ) !== 3 ) {
            return false;
        }
        $time_parts = explode( ':', $date_time_parts[1] );
        if ( ! $time_parts || count( $time_parts ) !== 3 ) {
            return false;
        }
        $parts = array(
            'year'  => absint( $date_parts[0] ),
            'month' => absint( $date_parts[1] ),
            'day'   => absint( $date_parts[2] ),
            'hour'  => absint( $time_parts[0] ),
            'min'   => absint( $time_parts[1] ),
            'sec'   => absint( $time_parts[2] ),
        );
        
        return $parts;
    }
}