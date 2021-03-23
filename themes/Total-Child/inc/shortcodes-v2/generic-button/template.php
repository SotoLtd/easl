<?php
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $url
 * @var $new_tab
 * @var $downloadable
 * @var $color
 * @var $size
 * @var $align
 * @var $show_arrow
 * @var $el_id
 * @var $css
 * @var $add_icon
 * @var $i_align
 * @var $i_type
 * @var $i_icon_fontawesome
 * @var $i_icon_openiconic
 * @var $i_icon_typicons
 * @var $i_icon_entypo
 * @var $i_icon_linecons
 * @var $i_icon_pixelicons
 * Shortcode class EASL_VC_Generic_Button
 * @var $this EASL_VC_Generic_Button
 */
$title        = '';
$url          = '';
$new_tab      = '';
$downloadable = '';
$color        = '';
$size         = '';
$align        = '';
$show_arrow   = '';
$add_icon = $i_align = $i_type = $i_icon_entypo = $i_icon_fontawesome = $i_icon_linecons = $i_icon_pixelicons = $i_icon_typicons = '';

$el_id = $el_class = $css_animation = $css = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$title = trim( $title );
$title = wp_kses( $title, array(
    'span'   => array(
        'class' => array(),
        'style' => array(),
    ),
    'em'     => array(),
    'strong' => array(),
    'br'     => array(),
) );
$url   = trim( $url );

if ( $title && $url ) {
    $button_classes = array( 'easl-generic-button' );
    if ( EASL_VC_Generic_Button_Container::$active == true ) {
        $align = 'inline';
    }
    $button_classes[] = EASL_VC_Generic_Button::get_color_class( $color );
    $button_classes[] = EASL_VC_Generic_Button::get_size_class( $size );
    $button_classes[] = EASL_VC_Generic_Button::get_align_class( $align );
    
    $icon_html = '';
    if ( 'true' === $add_icon ) {
        $button_classes[] = 'egb-icon-' . $i_align;
        vc_icon_element_fonts_enqueue( $i_type );
        
        if ( isset( ${'i_icon_' . $i_type} ) ) {
            if ( 'pixelicons' === $i_type ) {
                $icon_wrapper = true;
            }
            $icon_class = ${'i_icon_' . $i_type};
        } else {
            $icon_class = 'fa fa-adjust';
        }
        
        if ( $icon_wrapper ) {
            $icon_html = '<span class="easl-generic-button-icon"><i class="vc_btn3-icon"><span class="vc_btn3-icon-inner ' . esc_attr( $icon_class ) . '"></span></i></span>';
        } else {
            $icon_html = '<span class="easl-generic-button-icon"><i class="vc_btn3-icon ' . esc_attr( $icon_class ) . '"></i></span>';
        }
    }
    
    
    $css_class = $this->getCSSAnimation( $css_animation );
    $css_class .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
    $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class, $this->easlGetSettings('base'), $atts );
    
    if ( $css_class ) {
        $button_classes[] = $css_class;
    }
    
    $wrapper_attributes = array();
    if ( ! empty( $el_id ) ) {
        $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
    }
    
    if ( count( $button_classes ) > 0 ) {
        $wrapper_attributes[] = 'class="' . implode( ' ', $button_classes ) . '"';
    }
    
    if ( $new_tab == 'true' ) {
        $wrapper_attributes[] = 'target="_blank"';
    }
    
    if ( $downloadable == 'true' ) {
        $wrapper_attributes[] = 'download="' . basename( parse_url( $url, PHP_URL_PATH ) ) . '"';
        if ( !$icon_html ) {
            $icon_html            = '<span class="easl-generic-button-icon"><span class="ticon ticon-download"></span></span>';
        }
    }
    
    if ( !$icon_html && ($show_arrow == 'true') ) {
        $icon_html = '<span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span>';
    }
    
    $wrapper_attributes[] = 'href="' . esc_url( $url ) . '"';
    
    $output = '';
    if ( $align == 'center' ) {
        $output .= '<div class="easl-generic-button-wrap easl-content-center">';
    }
    
    if ( 'left' === $i_align ) {
        $output .= '<a ' . implode( ' ', $wrapper_attributes ) . '>' . $icon_html . $title . '</a>';
    } else {
        $output .= '<a ' . implode( ' ', $wrapper_attributes ) . '>' . $title . $icon_html . '</a>';
    }
    
    if ( $align == 'center' ) {
        $output .= '</div>';
    }
    echo $output;
}