<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Icon_Widget extends WPBakeryShortCode {

	/**
	 * Get icon widget dropdown data
	 * @return array
	 */
	public static function get_icon_widgets_dd() {
		$widgets_dd    = array( __( 'No Icon Widgets found', 'total-child' ) => '' );
		$icons_widgets = get_posts( array(
			'post_type'      => EASL_Icon_Widget_Config::get_slug(),
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'title',
		) );
		if ( ! $icons_widgets ) {
			return $widgets_dd;
		}
		$widgets_dd = array( __( 'Select an icon widget', 'total-child' ) => '' );
		foreach ( $icons_widgets as $widget ) {
			$widgets_dd[ get_the_title( $widget->ID ) ] = $widget->ID;
		}

		return $widgets_dd;
	}

}

vc_lean_map( 'easl_icon_widget', null, get_theme_file_path( 'inc/shortcodes/easl-icon-widget/map.php' ) );