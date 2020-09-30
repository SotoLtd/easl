<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( have_rows( 'se_sidebar_top_widgets' ) ) {
	while ( have_rows( 'se_sidebar_top_widgets' ) ) {
		the_row();
		$widget_type = get_sub_field( 'type' );
		if ( $widget_type ) {
			$widget_type = str_replace( '_', '-', $widget_type );
			get_template_part( 'partials/event/widgets/' . $widget_type );
		}
	}
}