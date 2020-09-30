<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Table_Of_Content extends WPBakeryShortCode {
	public function get_items($items){
		$items_parsed = array();
		if ( strlen( $items ) > 0 ) {
			$items_parsed = vc_param_group_parse_atts( $items );
		}
		if(empty($items_parsed)) {
			$items_parsed = array();
		}
		$items_data = array();
		foreach ($items_parsed as $item) {
			if(empty($item['i_title'])) {
				continue;
			}
			$items_data[] = array(
				'title' => $item['i_title'],
				'target' => $item['i_target']
			);
		}
		return $items_data;
	}
}

vc_lean_map( 'easl_table_of_content', null, get_theme_file_path( 'inc/shortcodes/easl-table-content/map.php' ) );