<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

class EASL_VC_EASL_Toggle extends EASL_ShortCode_Container {
    static $current_tab_id = null;
    /**
     * @param $atts
     * @return string
     * @throws \Exception
     */
    public function getHeading( $atts ) {
        return '<h4>' . esc_html( $atts['title'] ) . '</h4>';
    }
    
    /**
     * @param $atts
     * @param null $content
     *
     * @return string
     * @throws \Exception
     */
    public function contentAdmin( $atts, $content = null ) {
        $width = '';
        
        $atts = shortcode_atts( $this->predefined_atts, $atts );
        extract( $atts );
        $this->atts = $atts;
        $output = '';
        
        $output .= '<div ' . $this->mainHtmlBlockParams( $width, 1 ) . '>';
        if ( $this->backened_editor_prepend_controls ) {
            $output .= $this->getColumnControls( $this->settings( 'controls' ) );
        }
        $output .= '<div class="wpb_element_wrapper">';
        
        if ( isset( $this->settings['custom_markup'] ) && '' !== $this->settings['custom_markup'] ) {
            $markup = $this->settings['custom_markup'];
            $output .= $this->customMarkup( $markup );
        } else {
            $output .= $this->paramsHtmlHolders( $atts );
            $output .= '<div ' . $this->containerHtmlBlockParams( $width, 1 ) . '>';
            $output .= do_shortcode( shortcode_unautop( $content ) );
            $output .= '</div>';
        }
        
        $output .= '</div>';
        if ( $this->backened_editor_prepend_controls ) {
            $output .= $this->getColumnControls( 'add', 'bottom-controls' );
            
        }
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     *
     * @return string
     */
    public function containerContentClass() {
        return 'wpb_column_container vc_container_for_children vc_toggle_content vc_clearfix';
    }
    
}