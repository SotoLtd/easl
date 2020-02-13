<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_CAG_Members
 * @var $this EASL_VC_CAG_Members
 */
$title = $element_width = $view_all_link = $view_all_url = $view_all_text = $el_class = $el_id = $css_animation = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation = $this->getCSSAnimation($css_animation);

if(!$view_all_text){
	$view_all_text = 'View all Events';
}

if($title && $view_all_link){
	$title .= '<a class="easl-events-all-link" href="'. esc_url($view_all_url) .'">' . $view_all_text . '</a>';
}
easlenqueueTtaScript();

$rows = '';
$rows .= '
	<div class="cag-members-row clr">
		<div class="cagm-thumb">
			<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2017/11/thumb-femke.jpg"/>
		</div>
		<div class="cagm-text">
			<h5>Femke</h5>
			<h6>Heindryckx</h6>
		</div>
	</div>
	';
$rows .= '
	<div class="cag-members-row clr">
		<div class="cagm-thumb">
			<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2017/11/thumb-giacomo.jpg"/>
		</div>
		<div class="cagm-text">
			<h5>Giacomo</h5>
			<h6>Germani</h6>
		</div>
	</div>
	';
$rows .= '
	<div class="cag-members-row clr">
		<div class="cagm-thumb">
			<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2017/11/thumb-rodrigo.jpg"/>
		</div>
		<div class="cagm-text">
			<h5>Rodrigo</h5>
			<h6>Liberal</h6>
		</div>
	</div>
	';
$rows .= '
	<div class="cag-members-row clr">
		<div class="cagm-thumb">
			<img alt="" src="' . EASL_HOME_URL . '/wp-content/uploads/2017/11/thumb-upkar.jpg"/>
		</div>
		<div class="cagm-text">
			<h5>Upkar</h5>
			<h6>Gill</h6>
		</div>
	</div>
	';

$class_to_filter = 'wpb_easl_cag_members wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );


$html = '<div class="cag-members-wrap">
			'. $rows .'
		</div>';

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$output = '
	<div ' . implode( ' ', $wrapper_attributes ) . ' class="' . esc_attr( trim( $css_class ) ) . '">
		' . wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_easl_widget_heading' ) ) . '
			' . $html . '
	</div>
';

echo $output;
wp_get_archives();