<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

return array(
    'name'                    => __( 'EASL Button Container', 'total' ),
    'base'                    => 'easl_generic_button_container',
    'is_container'            => true,
    'show_settings_on_create' => false,
    'as_parent'               => array(
        'only' => 'easl_generic_button',
    ),
    'category'                => __( 'EASL', 'total' ),
    'description'             => __( 'Diplay a list of Buttons.', 'total' ),
    //'icon' => 'vcex-icon ticon ticon-th',
    'php_class_name'          => 'EASL_VC_Generic_Button_Container',
    'js_view'                 => 'VcColumnView',
    'params'                  => array(
        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Align Buttons', 'js_composer' ),
            'param_name' => 'align',
            'value'      => array(
                __( 'left', 'total' )          => 'left',
                __( 'Right', 'total' )         => 'right',
                __( 'Center', 'total' )        => 'center',
                __( 'Space Between', 'total' ) => 'space_between',
            ),
        ),
        vc_map_add_css_animation(),
        array(
            'type'        => 'el_id',
            'heading'     => __( 'Element ID', 'js_composer' ),
            'param_name'  => 'el_id',
            'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
        ),
        array(
            'type'        => 'textfield',
            'heading'     => __( 'Extra class name', 'js_composer' ),
            'param_name'  => 'el_class',
            'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => __( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            'group'      => __( 'Design Options', 'js_composer' ),
        ),
    ),
);
