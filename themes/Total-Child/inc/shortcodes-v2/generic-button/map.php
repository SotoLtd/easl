<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$pixel_icons = vc_pixel_icons();
require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-icon-element.php' );

$icons_params = vc_map_integrate_shortcode( vc_icon_element_params(), 'i_', '', array(
    'include_only_regex' => '/^(type|icon_\w*)/',
    // we need only type, icon_fontawesome, icon_blabla..., NOT color and etc
), array(
    'element' => 'add_icon',
    'value'   => 'true',
) );
// populate integrated vc_icons params.
if ( is_array( $icons_params ) && ! empty( $icons_params ) ) {
    foreach ( $icons_params as $key => $param ) {
        if ( is_array( $param ) && ! empty( $param ) ) {
            if ( 'i_type' === $param['param_name'] ) {
                // append pixelicons to dropdown
                $icons_params[ $key ]['value'][ esc_html__( 'Pixel', 'js_composer' ) ] = 'pixelicons';
            }
            if ( isset( $param['admin_label'] ) ) {
                // remove admin label
                unset( $icons_params[ $key ]['admin_label'] );
            }
            $icons_params[ $key ]['group'] = __( 'Icon', 'total-child' );
        }
    }
}

return array(
    'name'           => __( 'EASL Button', 'total-child' ),
    'base'           => 'easl_generic_button',
    'category'       => __( 'EASL', 'total-child' ),
    'description'    => __( 'Add a button', 'total-child' ),
    'icon'           => 'vcex-icon ticon ticon-external-link-square',
    'php_class_name' => 'EASL_VC_Generic_Button',
    'params'         => array_merge( array(
        array(
            'type'        => 'textfield',
            'heading'     => __( 'Title', 'total-child' ),
            'param_name'  => 'title',
            'description' => __( 'Enter button title.', 'total-child' ),
            'admin_label' => true,
        ),
        array(
            'type'        => 'textfield',
            'heading'     => __( 'URL', 'total-child' ),
            'param_name'  => 'url',
            'description' => __( 'Enter button URL.', 'total-child' ),
            'admin_label' => true,
        ),
        array(
            'type'       => 'vcex_ofswitch',
            'std'        => 'false',
            'heading'    => __( 'Open in New Tab', 'total-child' ),
            'param_name' => 'new_tab',
        ),
        array(
            'type'       => 'vcex_ofswitch',
            'std'        => 'false',
            'heading'    => __( 'Downloadable', 'total-child' ),
            'param_name' => 'downloadable',
        ),
        array(
            'type'             => 'dropdown',
            'heading'          => __( 'Color', 'total-child' ),
            'edit_field_class' => 'vc_col-xs-12 vc_colored-dropdown',
            'param_name'       => 'color',
            'std'              => 'primary',
            'value'            => array(
                __( 'Primary(Blue)', 'total-child' )         => 'primary',
                __( 'Secondary(Light Blue)', 'total-child' ) => 'secondary',
                __( 'Red', 'total-child' )                   => 'red',
                __( 'Teal', 'total-child' )                  => 'teal',
                __( 'Orange', 'total-child' )                => 'orange',
                __( 'Gray', 'total-child' )                  => 'gray',
                __( 'Yellow', 'total-child' )                => 'yellow',
            ),
            'group'            => __( 'Style', 'total-child' ),
            'admin_label'      => true,
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Size', 'total-child' ),
            'param_name'  => 'size',
            'std'         => 'small',
            'value'       => array(
                __( 'Small', 'total-child' )      => 'small',
                __( 'Medium', 'total-child' )     => 'medium',
                __( 'Large', 'total-child' )      => 'large',
                __( 'Full Width', 'total-child' ) => 'fullwidth',
            ),
            'group'       => __( 'Style', 'total-child' ),
            'admin_label' => true,
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => __( 'Alignment', 'total-child' ),
            'param_name'  => 'align',
            'std'         => 'inline',
            'value'       => array(
                __( 'Inline', 'total-child' ) => 'inline',
                __( 'Left', 'total-child' )   => 'left',
                __( 'Center', 'total-child' ) => 'center',
                __( 'Right', 'total-child' )  => 'right',
            ),
            'group'       => __( 'Style', 'total-child' ),
            'admin_label' => true,
        ),
        array(
            'type'       => 'checkbox',
            'heading'    => esc_html__( 'Add icon?', 'js_composer' ),
            'param_name' => 'add_icon',
            'group'            => __( 'Icon', 'total-child' ),
        ),
        array(
            'type'        => 'dropdown',
            'heading'     => esc_html__( 'Icon Alignment', 'js_composer' ),
            'description' => esc_html__( 'Select icon alignment.', 'js_composer' ),
            'param_name'  => 'i_align',
            'value'       => array(
                esc_html__( 'Left', 'js_composer' )  => 'left',
                // default as well
                esc_html__( 'Right', 'js_composer' ) => 'right',
            ),
            'std' => 'right',
            'dependency'  => array(
                'element' => 'add_icon',
                'value'   => 'true',
            ),
            'group'            => __( 'Icon', 'total-child' ),
        )
    ),
        $icons_params,
        array(
            array(
                'type'        => 'iconpicker',
                'heading'     => esc_html__( 'Icon', 'js_composer' ),
                'param_name'  => 'i_icon_pixelicons',
                'value'       => 'vc_pixel_icon vc_pixel_icon-alert',
                'settings'    => array(
                    'emptyIcon' => false,
                    // default true, display an "EMPTY" icon?
                    'type'      => 'pixelicons',
                    'source'    => $pixel_icons,
                ),
                'dependency'  => array(
                    'element' => 'i_type',
                    'value'   => 'pixelicons',
                ),
                'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
                'group'            => __( 'Icon', 'total-child' ),
            )
        ),
        array(
            array(
                'type'		 => 'vcex_ofswitch',
                'std'		 => 'true',
                'heading'	 => __( 'Show Arrow', 'total-child' ),
                'param_name' => 'show_arrow',
                'group' => __( 'Icon', 'total-child' ),
                'dependency'  => array(
                    'element' => 'add_icon',
                    'value_not_equal_to'   => 'true',
                ),
            ),
            vc_map_add_css_animation(),
            array(
                'type'        => 'el_id',
                'heading'     => __( 'Element ID', 'total-child' ),
                'param_name'  => 'el_id',
                'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
            ),
            array(
                'type'        => 'textfield',
                'heading'     => __( 'Extra class name', 'total-child' ),
                'param_name'  => 'el_class',
                'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-child' ),
            ),
            array(
                'type'       => 'css_editor',
                'heading'    => __( 'CSS box', 'js_composer' ),
                'param_name' => 'css',
                'group'      => __( 'Design Options', 'js_composer' ),
            ),
        )
    ),
);
