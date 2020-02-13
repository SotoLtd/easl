<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

	class EASL_VC_S3D_Slider extends WPBakeryShortCodesContainer {

		/**
		 * Count number of items
		 * @var int
		 */
		public static $items_count	 = 0;

		/**
		 * Save contains item data
		 * @var array
		 */
		public static $items_data	 = array();
		public static $data = array();

		/**
		 * Reset Items data
		 */
		public function reset_items_data() {
			self::$items_count	 = 0;
			self::$items_data	 = array();
			self::$data			 = array();
		}
		
		public function enqueue_fronend_assets() {
			//storyline-3d-slider
			wp_enqueue_style('fontawesome-css', get_theme_file_uri('assets/lib/storyline-3d-slider/css/font-awesome/css/font-awesome.css'));
			wp_enqueue_style('prettyPhoto-css', get_theme_file_uri('assets/lib/storyline-3d-slider/css/prettyPhoto.css'));
			wp_enqueue_style('flexslider-css', get_theme_file_uri('assets/lib/storyline-3d-slider/css/flexslider.css'));
			wp_enqueue_style('s3ds-mainstyle-css', get_theme_file_uri('assets/lib/storyline-3d-slider/css/style.css'));
			
			wp_deregister_script('prettyphoto');
			wp_deregister_script('flexslider');
			
			wp_enqueue_script('modernizr-custom-79639', get_theme_file_uri('assets/lib/storyline-3d-slider/js/modernizr.custom.79639.js'), array('jquery'), null, true);
			wp_enqueue_script('prettyphoto', get_theme_file_uri('assets/lib/storyline-3d-slider/js/jquery.prettyPhoto.js'), array('jquery'), null, true);
			wp_enqueue_script('s3ds-all-functions', get_theme_file_uri('assets/lib/storyline-3d-slider/js/all-functions.js'), array('jquery'), null, true);
			wp_enqueue_script('s3ds-classList', get_theme_file_uri('assets/lib/storyline-3d-slider/js/classList.js'), array('jquery'), null, true);
			wp_enqueue_script('s3ds-jquery', get_theme_file_uri('assets/lib/storyline-3d-slider/js/bespoke.js'), array('jquery'), null, true);
			wp_enqueue_script('flexslider', get_theme_file_uri('assets/lib/storyline-3d-slider/js/jquery.flexslider.js'), array('jquery'), null, true);
			wp_enqueue_script('s3ds-script', get_theme_file_uri('assets/lib/storyline-3d-slider/js/s3ds-script.js'), array('jquery'), null, true);
		}

	}

}
