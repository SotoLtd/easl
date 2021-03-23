<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Icon_Widget_Grid extends EASL_ShortCode_Container {
    private static $grid_active = false;
    private static $grid_column_num = 0;
    
    public static function set_grid_data( $column ) {
        self::$grid_active     = true;
        self::$grid_column_num = $column;
    }
    
    public static function reset_grid_data() {
        self::$grid_active     = false;
        self::$grid_column_num = 0;
    }
    
    public static function is_grid_active() {
        return self::$grid_active;
    }
}