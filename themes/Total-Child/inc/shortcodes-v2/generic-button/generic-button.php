<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_VC_Generic_Button extends EASL_ShortCode {
    public static function get_color_class( $color ) {
        $colors = array(
            'blue',
            'lightblue',
            'red',
            'teal',
            'orange',
            'gray',
            'yellow',
        );
        if ( $color == 'primary' ) {
            $color = 'blue';
        }
        if ( $color == 'secondary' ) {
            $color = 'lightblue';
        }
        if ( ! in_array( $color, $colors ) ) {
            $color = 'blue';
        }
        
        return 'easl-color-' . $color;
    }
    
    public static function get_size_class( $size ) {
        $sizes = array(
            'small',
            'medium',
            'large',
            'fullwidth',
        );
        if ( ! in_array( $size, $sizes ) ) {
            $size = 'small';
        }
        
        return 'easl-size-' . $size;
    }
    
    public static function get_align_class( $align ) {
        $aligns = array(
            'inline',
            'left',
            'center',
            'right',
        );
        if ( ! in_array( $align, $aligns ) ) {
            $align = 'inline';
        }
        
        return 'easl-align-' . $align;
    }
}