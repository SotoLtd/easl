<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_VC_Misc_List_Item extends WPBakeryShortCode {

}

vc_lean_map( 'easl_misc_list_item', null, get_theme_file_path( 'inc/shortcodes/easl-misc-list-item/map.php' ) );