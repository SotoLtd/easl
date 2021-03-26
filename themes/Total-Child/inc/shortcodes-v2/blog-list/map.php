<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

return array(
    'name'           => __( 'EASL Blog List', 'total-child' ),
    'base'           => 'easl_blog_list',
    'category'       => __( 'EASL', 'total' ),
    'icon'           => 'vcex-icon ticon ticon-list-alt',
    'php_class_name' => 'EASL_VC_Blog_List',
    'params'         => array(
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
            'type'        => 'textfield',
            'heading'     => __( 'Number of blogs', 'total-child' ),
            'param_name'  => 'limit',
            'value'       => '6',
            'description' => __( 'Enter the limit of blogs to show. Leave empty to show all.', 'total-child' ),
            'group'       => __( 'Query', 'total-child' ),
        ),
        array(
            'type'       => 'vcex_ofswitch',
            'std'        => 'true',
            'heading'    => __( 'Show Filter', 'total-child' ),
            'param_name' => 'show_filter',
            'group'      => __( 'View', 'total-child' ),
        ),
        array(
            'type'        => 'textfield',
            'heading'     => __( 'Excerpt Length', 'total-child' ),
            'param_name'  => 'excerpt_length',
            'value'       => '32',
            'description' => __( 'Enter excerpt words limit.', 'total-child' ),
            'group'       => __( 'View', 'total-child' ),
        ),
        array(
            'type'        => 'vcex_ofswitch',
            'std'         => 'true',
            'heading'     => __( 'Display Blog Sidebar?', 'total-child' ),
            'param_name'  => 'display_sidebar',
            'description' => __( 'Enable to display blog sidebar.', 'total-child' ),
            'group'       => __( 'View', 'total-child' ),
        ),
        array(
            'type'       => 'css_editor',
            'heading'    => __( 'CSS box', 'total-child' ),
            'param_name' => 'css',
            'group'      => __( 'Design Options', 'total-child' ),
        ),
    ),
);
