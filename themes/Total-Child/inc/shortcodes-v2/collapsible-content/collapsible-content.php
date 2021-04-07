<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_VC_Collapsible_Content extends EASL_ShortCode_Container {
    private static $instance = 0;
    
    public static function generate_id() {
        self::$instance ++;
        
        return 'easl-cc-' . self::$instance;
    }
}