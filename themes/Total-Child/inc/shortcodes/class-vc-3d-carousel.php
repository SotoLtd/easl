<?php

if ( !defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

	class EASL_VC_3D_Carousel extends WPBakeryShortCodesContainer {

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
			wp_enqueue_style('bee3D', get_theme_file_uri('assets/lib/bee3d/css/bee3D.css'));

			wp_enqueue_script('bee3D_classie', get_theme_file_uri('assets/lib/bee3d/js/vendor/classie.js'), array('jquery'), null, true);
			wp_enqueue_script('bee3D', get_theme_file_uri('assets/lib/bee3d/js/bee3D.js'), array('jquery'), null, true);
		}

	}

}
