<?php
// Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path('inc/custom-widgets/easl-newsletters.php');
require_once get_theme_file_path('inc/custom-widgets/easl-recent-items.php');

function easl_register_custom_widgets() {
	if(class_exists( 'EASL_Newsletters_Widget' )){
		register_widget('EASL_Newsletters_Widget');
	}
	if(class_exists( 'EASL_Recent_Items_Widget' )){
		register_widget('EASL_Recent_Items_Widget');
	}
}
add_action( 'widgets_init', 'easl_register_custom_widgets' );