<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Callout extends WPBakeryShortCode {
}

vc_lean_map( 'easl_callout', null, get_theme_file_path( 'inc/shortcodes/easl-callout/map.php' ) );