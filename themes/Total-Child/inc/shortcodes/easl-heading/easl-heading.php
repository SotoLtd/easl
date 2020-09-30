<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Heading extends WPBakeryShortCode {
}

vc_lean_map( 'easl_heading', null, get_theme_file_path( 'inc/shortcodes/easl-heading/map.php' ) );