<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

return array(
    'name'                    => __( 'EASL Programme Table', 'total-child' ),
    'base'                    => 'easl_table',
    'is_container'            => false,
    'show_settings_on_create' => false,
    'category'                => __( 'EASL', 'total-child' ),
    'description'             => __( 'EASL programme table', 'total-child' ),
    'icon'                    => 'vcex-icon ticon ticon-table',
    'php_class_name'          => 'EASL_VC_Table',
    'params'                  => array(
        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Table', 'total-child' ),
            'param_name'  => 'table',
            'value'       => EASL_VC_Table::get_items_dd(),
            'admin_label' => true,
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
