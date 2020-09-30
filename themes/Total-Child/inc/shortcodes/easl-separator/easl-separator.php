<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Separator extends WPBakeryShortCode {
}

vc_lean_map( 'easl_separator', null, get_theme_file_path( 'inc/shortcodes/easl-separator/map.php' ) );