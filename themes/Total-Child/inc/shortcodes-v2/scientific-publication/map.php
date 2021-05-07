<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
    'name'           => __( 'EASL Scientific Publication', 'total-child' ),
    'base'           => 'easl_scientific_publication',
    'category'       => __( 'EASL', 'total-child' ),
    'description'    => __( 'EASL Scientific Publication', 'total-child' ),
    'icon'           => 'vcex-icon ticon ticon-book',
    'php_class_name' => 'EASL_VC_Scientific_Publication',
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
        // Query
        array(
            'type'        => 'textfield',
            'heading'     => __( 'Posts Per Page', 'total-child' ),
            'param_name'  => 'posts_per_page',
            'value'       => '9',
            'description' => __( 'Leave empty to display all.', 'total-child' ),
            'group'       => __( 'Query', 'total-child' ),
        ),
        array(
            'type'       => 'vcex_ofswitch',
            'std'        => 'false',
            'heading'    => __( 'Pagination', 'total-child' ),
            'param_name' => 'pagination',
            'group'      => __( 'Query', 'total-child' ),
        ),
        array(
            'type'               => 'autocomplete',
            'heading'            => __( 'Include Categories', 'total-child' ),
            'param_name'         => 'include_categories',
            'param_holder_class' => 'vc_not-for-custom',
            'admin_label'        => true,
            'settings'           => array(
                'multiple'       => true,
                'min_length'     => 1,
                'groups'         => false,
                'unique_values'  => true,
                'display_inline' => true,
                'delay'          => 0,
                'auto_focus'     => true,
            ),
            'group'              => __( 'Query', 'total-child' ),
        ),
        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Order', 'total-child' ),
            'param_name' => 'order',
            'group'      => __( 'Query', 'total-child' ),
            'value'      => array(
                __( 'Default', 'total-child' ) => '',
                __( 'DESC', 'total-child' )    => 'DESC',
                __( 'ASC', 'total-child' )     => 'ASC',
            ),
        ),
        array(
            'type'       => 'dropdown',
            'heading'    => __( 'Order By', 'total-child' ),
            'param_name' => 'orderby',
            'value'      => array(
                __( 'Publication Date', 'total' ) => '',
                __( 'Title', 'total' )            => 'title',
            ),
            'group'      => __( 'Query', 'total-child' ),
        ),
        // View
        array(
            'type'        => 'vcex_ofswitch',
            'std'         => 'false',
            'heading'     => __( 'Hide Topic?', 'total-child' ),
            'param_name'  => 'hide_topic',
            'group'       => __( 'View', 'total-child' ),
            'description' => __( 'Hide Topic.', 'total-child' ),
        ),
//        array(
//            'type'        => 'vcex_ofswitch',
//            'std'         => 'false',
//            'heading'     => __( 'Deny Detail page?', 'total-child' ),
//            'param_name'  => 'deny_detail_page',
//            'group'       => __( 'View', 'total-child' ),
//            'description' => __( 'Deny Detail page.', 'total-child' ),
//        ),
        array(
            'type'        => 'vcex_ofswitch',
            'std'         => 'false',
            'heading'     => __( 'Hide Excerpt?', 'total-child' ),
            'param_name'  => 'hide_excerpt',
            'group'       => __( 'View', 'total-child' ),
            'description' => __( 'Hide Excerpt.', 'total-child' ),
        ),
        //Take Me To Group
        array(
            'type'       => 'vcex_ofswitch',
            'std'        => 'true',
            'heading'    => __( 'Enable', 'total-child' ),
            'param_name' => 'enable_related_links',
            'group'      => __( 'Related Links', 'total-child' ),
        ),
        array(
            'type'       => 'textfield',
            'heading'    => __( 'Title', 'total-child' ),
            'param_name' => 'relink_title',
            'value'      => 'Take me to:',
            'group'      => __( 'Related Links', 'total-child' ),
            'dependency' => array(
                'element' => 'enable_related_links',
                'value'   => array( 'true' ),
            ),
        ),
        array(
            'type'       => 'param_group',
            'heading'    => __( 'Related Links', 'total-child' ),
            'param_name' => 'related_links',
            'group'      => __( 'Related Links', 'total-child' ),
            'dependency' => array(
                'element' => 'enable_related_links',
                'value'   => array( 'true' ),
            ),
            'value'      => urlencode( json_encode( array(
                array(
                    'rlink' => '',
                ),
            ) ) ),
            'params'     => array(
                array(
                    'type'        => 'vc_link',
                    'value'       => '',
                    'param_name'  => 'rlink',
                    'heading'     => __( 'Related link data', 'total-child' ),
                    'admin_label' => true,
                ),
            ),
        ),
        // Design Options
        array(
            'type'       => 'css_editor',
            'heading'    => __( 'CSS box', 'total-child' ),
            'param_name' => 'css',
            'group'      => __( 'Design Options', 'total-child' ),
        ),
    ),
);
