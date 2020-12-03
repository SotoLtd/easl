<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

add_action( 'vc_after_init', 'easl_vc_modify_existing_shortcode_params', 40 );
add_filter( 'vc_tta_accordion_general_classes', 'easl_tta_sc_css_classes', 20, 3 );
add_filter( 'vc_shortcodes_custom_css', 'easl_vc_shortcodes_custom_css', 20, 2 );

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
