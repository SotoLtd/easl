<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Logo_Grid extends WPBakeryShortCode {
}

vc_lean_map( 'easl_logo_grid', null, get_theme_file_path( 'inc/shortcodes/easl-logo-grid/map.php' ) );