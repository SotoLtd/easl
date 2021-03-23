<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

return array(
    'name'                    => __( 'EASL Icon Widget Grid', 'total-child' ),
    'base'                    => 'easl_icon_widget_grid',
    'is_container'            => true,
    'show_settings_on_create' => false,
    'as_parent'               => array(
        'only' => 'easl_icon_widget',
    ),
    'category'                => __( 'EASL', 'total-child' ),
    'description'             => __( 'EASL Icon Widget Grid', 'total-child' ),
    'icon'                    => 'easl-no-default-container-icon vcex-icon ticon ticon-th',
    'php_class_name'          => 'EASL_VC_Icon_Widget_Grid',
    'js_view'                 => 'VcColumnView',
    'params'                  => array(
        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Columns', 'total-child' ),
            'param_name' => 'columns',
            'value'      => array(
                __( '1', 'total' ) => '1',
                __( '2', 'total' ) => '2',
                __( '3', 'total' ) => '3',
                __( '4', 'total' ) => '4',
            ),
        ),
        vc_map_add_css_animation(),
        array(
            'type'        => 'el_id',
            'heading'     => __( 'Element ID', 'total-child' ),
            'param_name'  => 'el_id',
            'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'total-child' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
        ),
        array(
            'type'        => 'textfield',
            'heading'     => __( 'Extra class name', 'total-child' ),
            'param_name'  => 'el_class',
            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => __( 'CSS box', 'total-child' ),
            'param_name' => 'css',
            'group'      => __( 'Design Options', 'total-child' ),
        ),
    ),
);