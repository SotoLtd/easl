<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_Table_Of_Content extends EASL_ShortCode {
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
    public function get_color_class( $color, $base = 'color' ) {
        $colors = array(
            'blue',
            'lightblue',
            'red',
            'teal',
            'orange',
            'grey',
            'yellow',
            'white',
        );
        if ( $color == 'primary' ) {
            $color = 'blue';
        }
        if ( $color == 'secondary' ) {
            $color = 'lightblue';
        }
        if ( ! in_array( $color, $colors ) ) {
            $color = 'lightblue';
        }
        
        return "easl-{$base}-{$color}";
    }
    
    public function get_shape_class( $shape ) {
        $shapes = array(
            'rect',
            'rounded',
            'rounded_large',
        );
        if ( ! in_array( $shape, $shapes ) ) {
            $shape = 'rect';
        }
        return 'easl-shape-' . $shape;
    }
    
    public function get_size_class( $size ) {
        $sizes = array(
            'small',
            'medium',
            'large',
            'fullwidth',
        );
        if ( ! in_array( $size, $sizes ) ) {
            $size = 'small';
        }
        return 'easl-size-' . $size;
    }
}

vc_lean_map( 'easl_table_of_content', null, get_theme_file_path( 'inc/shortcodes/easl-table-content/map.php' ) );