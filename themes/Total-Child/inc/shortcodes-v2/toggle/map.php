<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

return array(
    'name'                      => __( 'EASL Toggle', 'total-child' ),
    'base'                      => 'easl_toggle',
    'icon'                      => 'icon-wpb-toggle-small-expand',
    'is_container'              => true,
    //'allowed_container_element' => 'vc_row',
    'show_settings_on_create'   => false,
    'category'                  => __( 'EASL', 'total-child' ),
    'description'               => __( 'Customise toggle element.', 'total-child' ),
    'php_class_name'            => 'EASL_VC_EASL_Toggle',
    //'js_view'                   => 'VcColumnView',
    'js_view'                   => 'VcToggleView',
    'params'                    => array(
        array(
            'type'             => 'textfield',
            'holder'           => 'h4',
            'class'            => 'vc_toggle_title',
            'heading'          => esc_html__( 'Toggle title', 'js_composer' ),
            'param_name'       => 'title',
            'value'            => esc_html__( 'Toggle title', 'js_composer' ),
            'description'      => esc_html__( 'Enter title of toggle block.', 'js_composer' ),
            'edit_field_class' => 'vc_col-sm-9',
        ),
        array(
            'type'               => 'dropdown',
            'param_name'         => 'color',
            'param_holder_class' => 'vc_colored-dropdown',
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
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Default state', 'js_composer' ),
            'param_name'  => 'open',
            'value'       => array(
                esc_html__( 'Closed', 'js_composer' ) => 'false',
                esc_html__( 'Open', 'js_composer' )   => 'true',
            ),
            'description' => esc_html__( 'Select "Open" if you want toggle to be open by default.', 'js_composer' ),
        ),
        vc_map_add_css_animation(),
        array(
            'type'        => 'el_id',
            'heading'     => esc_html__( 'Element ID', 'js_composer' ),
            'param_name'  => 'el_id',
            'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'js_composer' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
        ),
        array(
            'type'        => 'textfield',
            'heading'     => esc_html__( 'Extra class name', 'js_composer' ),
            'param_name'  => 'el_class',
            'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => esc_html__( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            'group'      => esc_html__( 'Design Options', 'js_composer' ),
        ),
    ),
);
