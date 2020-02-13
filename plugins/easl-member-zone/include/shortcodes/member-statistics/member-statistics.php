<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_MZ_Member_Statistics extends EASL_MZ_Shortcode {

	public function enqueue_map_scripts() {
		return;
		wp_enqueue_script( 'mz-geochart-script', 'https://www.gstatic.com/charts/loader.js', array(), '', true );
		wp_enqueue_script( 'mz-statmap-script', easl_mz_get_asset_url( '/js/stats-map.js' ), array(
			'jquery',
			'mz-geochart-script'
		), time(), true );

		$script_settings = array(
			'apiKey' => get_field( 'mz_map_api_key', 'option' )
		);

		wp_localize_script( 'mz-statmap-script', 'EASLMZMAP', $script_settings );
	}
}