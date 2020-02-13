<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_VC_Misc_List extends WPBakeryShortCodesContainer {
	private static $lilst_item_count = 0;
	static public function reset_list_items(){
		self::$lilst_item_count = 0;
	}
	static public function add_list_items(){
		self::$lilst_item_count++;
	}
	static public function has_list_items(){
		return self::$lilst_item_count > 0;
	}
}

vc_lean_map( 'easl_misc_list', null, get_theme_file_path( 'inc/shortcodes/easl-misc-list/map.php' ) );