<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

add_action( 'vc_after_init', 'easl_vc_modify_existing_shortcode_params', 40 );
add_filter( 'vc_tta_accordion_general_classes', 'easl_tta_sc_css_classes', 20, 3 );
add_filter( 'vc_shortcodes_custom_css', 'easl_vc_shortcodes_custom_css', 20, 2 );
add_filter( 'vc-tta-get-params-tabs-list', 'easl_vc_tta_tabs_list', 20, 4 );
add_filter( 'tiny_mce_before_init', 'easl_tiny_mce_settings', 20, 2 );
add_filter( 'vc_map_get_attributes', 'easl_vc_map_get_attributes', 20, 2 );

add_filter('vc_post_custom_css', 'easl_vc_post_custom_css', 20, 2 );

add_action('vc_backend_editor_enqueue_js_css', 'easl_vc_backend_scripts');

function easl_tta_sc_css_classes( $classes, $atts ) {
    if ( in_array( 'vc_tta-tabs', $classes ) && ! empty( $atts['tab_position'] ) && in_array( $atts['tab_position'], array(
            'left',
            'right'
        ) ) ) {
        $classes[] = 'vcsc_tta-tour';
        $classes[] = 'vcsc_tta-tour-' . $atts['tab_position'];
    }
    if ( in_array( 'vc_tta-tabs', $classes ) && ! empty( $atts['tab_position'] ) && in_array( $atts['tab_position'], array(
            'top',
            'bottom'
        ) ) ) {
        $classes[] = 'vcsc_tta-tab';
        $classes[] = 'vcsc_tta-tab-' . $atts['tab_position'];
    }
    
    return $classes;
}

function easl_vc_modify_existing_shortcode_params() {
    try {
        vc_remove_param( 'vc_tta_accordion', 'spacing' );
        vc_remove_param( 'vc_tta_accordion', 'gap' );
        vc_remove_param( 'vc_tta_accordion', 'c_align' );
        vc_remove_param( 'vc_tta_accordion', 'autoplay' );
        vc_remove_param( 'vc_tta_accordion', 'shape' );
        vc_update_shortcode_param( 'vc_tta_accordion', array(
            'param_name'  => 'no_fill',
            'heading'     => esc_html__( 'Add padding to content are', 'js_composer' ),
            'description' => esc_html__( 'Add padding to the panel body. Useful when adding  background to the accordion.', 'js_composer' ),
            'group' => esc_html__( 'Design Options', 'js_composer' ),
        ) );
        vc_update_shortcode_param( 'vc_tta_accordion', array(
            'param_name' => 'style',
            'value'      => array(
                esc_html__( 'Default', 'js_composer' )  => 'classic',
                esc_html__( 'Outlined', 'js_composer' ) => 'outline',
            )
        ) );
        vc_update_shortcode_param( 'vc_tta_accordion', array(
            'param_name' => 'color',
            'value'      => array(
                __( 'Default', 'total-child' )               => 'blue',
                __( 'Primary(Blue)', 'total-child' )         => 'blue',
                __( 'Secondary(Light Blue)', 'total-child' ) => 'lightblue',
                __( 'Red', 'total-child' )                   => 'red',
                __( 'Teal', 'total-child' )                  => 'teal',
                __( 'Orange', 'total-child' )                => 'orange',
                __( 'Grey', 'total-child' )                  => 'grey',
                __( 'Yellow', 'total-child' )                => 'yellow',
            ),
        ) );
        
        //vc_tta_tabs
        vc_remove_param( 'vc_tta_tabs', 'spacing' );
        vc_remove_param( 'vc_tta_tabs', 'gap' );
        vc_remove_param( 'vc_tta_tabs', 'alignment' );
        vc_remove_param( 'vc_tta_tabs', 'autoplay' );
        vc_remove_param( 'vc_tta_tabs', 'shape' );
        vc_remove_param( 'vc_tta_tabs', 'controls_size' );
        vc_remove_param( 'vc_tta_tabs', 'pagination_style' );
        vc_remove_param( 'vc_tta_tabs', 'pagination_color' );
        vc_remove_param( 'vc_tta_tabs', 'style' );
        vc_update_shortcode_param( 'vc_tta_tabs', array(
            'param_name' => 'color',
            'value'      => array(
                __( 'Default', 'total-child' )               => 'blue',
                __( 'Primary(Blue)', 'total-child' )         => 'blue',
                __( 'Secondary(Light Blue)', 'total-child' ) => 'lightblue',
                __( 'Red', 'total-child' )                   => 'red',
                __( 'Teal', 'total-child' )                  => 'teal',
                __( 'Orange', 'total-child' )                => 'orange',
                __( 'Grey', 'total-child' )                  => 'grey',
                __( 'Yellow', 'total-child' )                => 'yellow',
            ),
        ) );
        vc_update_shortcode_param( 'vc_tta_tabs', array(
            'heading'          => esc_html__( 'Add Padding to content area', 'js_composer' ),
            'param_name'       => 'no_fill_content_area',
            'description'      => esc_html__( 'Add padding to the toggle content. Useful when adding  background to the toggle.', 'js_composer' ),
            'group' => esc_html__( 'Design Options', 'js_composer' ),
        ) );
        vc_remove_param( 'vc_tta_tour', 'spacing' );
        vc_remove_param( 'vc_tta_tour', 'gap' );
        vc_remove_param( 'vc_tta_tour', 'alignment' );
        vc_remove_param( 'vc_tta_tour', 'autoplay' );
        vc_remove_param( 'vc_tta_tour', 'shape' );
        vc_remove_param( 'vc_tta_tour', 'controls_size' );
        vc_remove_param( 'vc_tta_tour', 'pagination_style' );
        vc_remove_param( 'vc_tta_tour', 'pagination_color' );
        vc_remove_param( 'vc_tta_tour', 'no_fill_content_area' );
        vc_remove_param( 'vc_tta_tour', 'style' );
        vc_update_shortcode_param( 'vc_tta_tour', array(
            'param_name' => 'color',
            'value'      => array(
                __( 'Default', 'total-child' )               => 'blue',
                __( 'Primary(Blue)', 'total-child' )         => 'blue',
                __( 'Secondary(Light Blue)', 'total-child' ) => 'lightblue',
                __( 'Red', 'total-child' )                   => 'red',
                __( 'Teal', 'total-child' )                  => 'teal',
                __( 'Orange', 'total-child' )                => 'orange',
                __( 'Grey', 'total-child' )                  => 'grey',
                __( 'Yellow', 'total-child' )                => 'yellow',
            ),
        ) );
        // vc_toggle
        vc_remove_param( 'vc_toggle', 'size' );
        vc_remove_param( 'vc_toggle', 'style' );
        vc_remove_param( 'vc_toggle', 'custom_font_container' );
        vc_remove_param( 'vc_toggle', 'custom_use_theme_fonts' );
        vc_remove_param( 'vc_toggle', 'custom_google_fonts' );
        vc_remove_param( 'vc_toggle', 'custom_el_id' );
        vc_remove_param( 'vc_toggle', 'custom_el_class' );
        vc_remove_param( 'vc_toggle', 'custom_css_animation' );
        vc_update_shortcode_param( 'vc_toggle', array(
            'param_name'  => 'color',
            'heading'     => esc_html__( 'Color', 'js_composer' ),
            'value'       => array(
                __( 'Default', 'total-child' )               => 'blue',
                __( 'Primary(Blue)', 'total-child' )         => 'blue',
                __( 'Secondary(Light Blue)', 'total-child' ) => 'lightblue',
                __( 'Red', 'total-child' )                   => 'red',
                __( 'Teal', 'total-child' )                  => 'teal',
                __( 'Orange', 'total-child' )                => 'orange',
                __( 'Grey', 'total-child' )                  => 'grey',
                __( 'Yellow', 'total-child' )                => 'yellow',
            ),
            'description' => esc_html__( 'Select title bg color.', 'js_composer' ),
        ) );
        vc_update_shortcode_param( 'vc_toggle', array(
            'heading'          => esc_html__( 'Add Padding to content area', 'js_composer' ),
            'param_name'       => 'use_custom_heading',
            'description'      => esc_html__( 'Add padding to the toggle content. Useful when adding  background to the toggle.', 'js_composer' ),
            'edit_field_class' => 'vc_col-sm-12',
            'group' => esc_html__( 'Design Options', 'js_composer' ),
        ) );
        vc_update_shortcode_param( 'vc_toggle', array(
            'param_name'       => 'title',
            'edit_field_class' => 'vc_col-sm-12',
        ) );
        // vc_tta_section
        vc_add_param( 'vc_tta_section', array(
            'type'  => 'dropdown',
            'param_name'  => 'color',
            'heading'     => esc_html__( 'Section or Tab Title Color', 'js_composer' ),
            'value'       => array(
                __( 'Default', 'total-child' )               => '',
                __( 'Primary(Blue)', 'total-child' )         => 'blue',
                __( 'Secondary(Light Blue)', 'total-child' ) => 'lightblue',
                __( 'Red', 'total-child' )                   => 'red',
                __( 'Teal', 'total-child' )                  => 'teal',
                __( 'Orange', 'total-child' )                => 'orange',
                __( 'Grey', 'total-child' )                  => 'grey',
                __( 'Yellow', 'total-child' )                => 'yellow',
            ),
            'description' => esc_html__( 'Select section/tab title bg color.', 'js_composer' ),
            'weight' => 1,
        ) );
    } catch ( Exception $e ) {
        unset( $e );
    }
}

function easl_vc_shortcodes_custom_css($custom_css, $post_id) {
    if(get_post_type($post_id) != 'event') {
        return $custom_css;
    }
    $event_subpage = easl_get_the_event_subpage();
    if ( ! $event_subpage ) {
        return $custom_css;
    }
    return $custom_css . get_metadata( 'post', $event_subpage->ID, '_wpb_shortcodes_custom_css', true );
}

function easl_vc_tta_tabs_list($html, $atts, $content, $sc_instance) {
    $isPageEditabe = vc_is_page_editable();
    $html = array();
    $html[] = '<div class="vc_tta-tabs-container">';
    $html[] = '<ul class="vc_tta-tabs-list">';
    if ( ! $isPageEditabe ) {
        $active_section = $sc_instance->getActiveSection( $atts, false );
        
        foreach ( WPBakeryShortCode_Vc_Tta_Section::$section_info as $nth => $section ) {
            $classes = array( 'vc_tta-tab' );
            if ( ( $nth + 1 ) === $active_section ) {
                $classes[] = $sc_instance->activeClass;
            }
            
            if(!empty($section['color'])) {
                $classes[] = 'easl-color-' . $section['color'];
            }
            
            $title = '<span class="vc_tta-title-text">' . $section['title'] . '</span>';
            if ( 'true' === $section['add_icon'] ) {
                $icon_html = $sc_instance->constructIcon( $section );
                if ( 'left' === $section['i_position'] ) {
                    $title = $icon_html . $title;
                } else {
                    $title = $title . $icon_html;
                }
            }
            $a_html = '<a href="#' . $section['tab_id'] . '" data-vc-tabs data-vc-container=".vc_tta">' . $title . '</a>';
            $html[] = '<li class="' . implode( ' ', $classes ) . '" data-vc-tab>' . $a_html . '</li>';
        }
    }
    
    $html[] = '</ul>';
    $html[] = '</div>';
    return $html;
}


function easl_tiny_mce_settings($mceInit, $editor_id) {
    //textcolor_map, $mceInit, $editor_id
    $mceInit['textcolor_map'] = json_encode(array(
        '5BC2E7', 'Light Blue',
        '004B87', 'Blue',
        '523178', 'Purple',
        '101820', 'Black',
        'A2ACAB', 'Grey',
        'EF3340', 'Red 1',
        'FF6720', 'Orange',
        '508541', 'Green',
        '826B6A', 'Brown',
        'DB0B5B', 'Red 2',
        '1A9586', 'Teal',
        'FFBF3F', 'Yellow'
    ));
    
    return $mceInit;
}
function easl_vc_map_get_attributes($atts, $tag ) {
    if('vc_tta_section' != $tag) {
        return $atts;
    }
    $tab_id = !empty($atts['tab_id']) ? $atts['tab_id'] : strtolower(sanitize_html_class($atts['title']));
    if(!empty(EASL_VC_EASL_Toggle::$current_tab_id )) {
        $atts['tab_id'] = EASL_VC_EASL_Toggle::$current_tab_id .'__' . $tab_id;
    }
    
    return $atts;
}
function easl_vc_backend_scripts() {
    wp_enqueue_script('easl_vc_scripts', get_stylesheet_directory_uri() . '/assets/js/admin/vc-custom-views.js', array(), EASL_THEME_VERSION, true);
}

function easl_vc_post_custom_css($post_custom_css, $id ) {
	$event_subpage_id = easl_get_the_event_subpage_id();
	if(!$event_subpage_id) {
		return $post_custom_css;
	}
	$post_custom_css .= get_metadata( 'post', $event_subpage_id, '_wpb_post_custom_css', true );
	return $post_custom_css;
}