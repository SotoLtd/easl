<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
class EASL_VC_Annual_Reports extends WPBakeryShortCode {

}
vc_lean_map( 'easl_annual_reports', null, get_theme_file_path( 'inc/shortcodes/easl-annual-reports/map.php' ) );