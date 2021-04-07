<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

return array(
    'name'                    => esc_html__( 'EASL Collapsible Content', 'total-child' ),
    'base'                    => 'easl_collapsible_content',
    'icon'                    => 'vcex-icon ticon ticon-arrows-v',
    'is_container'            => true,
    'show_settings_on_create' => false,
    'category'                => esc_html__( 'EASL', 'total' ),
    'description'             => esc_html__( 'Collapsible content', 'total-child' ),
    'js_view'                 => 'VcColumnView',
    'php_class_name'          => 'EASL_VC_Collapsible_Content',
    'params'                  => array(
        array(
            'type'        => 'textfield',
            'param_name'  => 'button_text',
            'heading'     => esc_html__( 'Button Open Text', 'total-child' ),
            'description' => esc_html__( 'Enter button open text.', 'total-child' ),
        ),
        array(
            'type'        => 'textfield',
            'param_name'  => 'button_text_close',
            'heading'     => esc_html__( 'Button Close Text', 'total-child' ),
            'description' => esc_html__( 'Enter button close text.', 'total-child' ),
        ),
        array(
            'type'        => 'checkbox',
            'param_name'  => 'open',
            'heading'     => esc_html__( 'Open by default', 'total-child' ),
        ),
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
            'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'js_composer' ),
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => esc_html__( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            'group'      => esc_html__( 'Design Options', 'js_composer' ),
        ),
    ),
);
