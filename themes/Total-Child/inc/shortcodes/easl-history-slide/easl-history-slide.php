<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'WPBakeryShortCode' ) ) {
	class EASL_VC_History_Slide extends WPBakeryShortCode {
		public function front_end_assets() {
			vcex_enqueue_carousel_scripts();
			wp_enqueue_style( 'jquery-ui-lib-style',get_stylesheet_directory_uri() . '/assets/lib/jquery-ui-1.12.1.custom/jquery-ui.css' );
			wp_enqueue_script( 'jquery-ui-slider');
			wp_enqueue_script('jquery-ui-touch-punch','https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', array('jquery', 'jquery-ui-slider', 'wpex-carousel'),false,true);
			wp_enqueue_script('history-timeline-slider-script',get_stylesheet_directory_uri() . '/assets/js/history-slider.js', array('jquery', 'jquery-ui-slider', 'wpex-carousel'),false,true);
		}
	}
}
vc_lean_map( 'easl_history_slide', null, get_theme_file_path( 'inc/shortcodes/easl-history-slide/map.php' ) );