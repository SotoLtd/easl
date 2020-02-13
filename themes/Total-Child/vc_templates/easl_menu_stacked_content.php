<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $css
 * @var $content - shortcode content
 * Shortcode class EASL_VC_Menu_Stacked_content
 * @var $this EASL_VC_Menu_Stacked_content
 */
$title = $nav_menu = $layout =  $el_class = $el_id = $css_animation = '';
$enable_right_menu_filter = $filter_all_link = $show_all_tabs_on_mobile = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if($enable_right_menu_filter == 'true') {
	$enable_right_menu_filter = true;
}else{
	$enable_right_menu_filter = false;
}
$filter_all_link = trim($filter_all_link);

$css_classes = 'easl-menu-stacked-content wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$css_classes .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );


if($layout == "vertical") {
	$container_class = 'easl-msc-container-vertical';
	$menu_wrap_class = "easl-msc-menu-wrap-vertical";
	$content_wrapp_class = "easl-msc-content-wrap-vertical";
	$css_classes .= ' easl-menu-stacked-content-vertical';
	$menu_class = 'msch-menu';
}else{
	$container_class = 'easl-msc-container';
	$menu_wrap_class = "easl-msc-menu-wrap";
	$content_wrapp_class = "easl-msc-content-wrap";
	$menu_class = 'msc-menu';
}


if($show_all_tabs_on_mobile == 'true') {
	$css_classes .= ' easl-msc-menu-mobile-menu-normal';
}

$menu_content = '';
if($nav_menu){
	$menu_content = wp_nav_menu(
		array(
			'menu' => $nav_menu,
			'container'		 => '',
			'menu_class'	 => $menu_class,
			'link_before'	 => '',
			'link_after'	 => '',
			'fallback_cb'	 => '',
			'echo'			 => false,
		)
	);
}
if($menu_content){
	$menu_content = '
		<div class="'.$menu_wrap_class.'">
			'. $menu_content .'
		</div>
		';
}

$wrapper_attributes = array();

$wrapper_attributes[] = 'class="' . esc_attr( trim( $css_classes ) ) . '"';
EASL_VC_Menu_Stacked_content::$right_menu_data = array();
EASL_VC_Menu_Stacked_content::$enable_right_menu_data = $enable_right_menu_filter;
$parsed_content = wpb_js_remove_wpautop( $content );
$right_menu_html = '';
if($enable_right_menu_filter && count(EASL_VC_Menu_Stacked_content::$right_menu_data) > 1) {
	$right_menu_html = '<div class="msc-rm-filter-container"><ul class="msc-filter-menu">';
	if($filter_all_link) {
		$right_menu_html .= '<li class="active" ><a href="#all">' . $filter_all_link . '</a></li>';
	}
	$rmf_count = 0;
	foreach(EASL_VC_Menu_Stacked_content::$right_menu_data as $rmitem){
		$rmf_item_calss = '';
		if(!$filter_all_link && (0 == $rmf_count)){
			$rmf_item_calss = ' class="active"';
		}
		$right_menu_html .= '<li' . $rmf_item_calss . '><a href="#'. $rmitem['id'] .'">' . $rmitem['title'] . '</a></li>';
		$rmf_count++;
	}
	$right_menu_html .= '</ul></div>';
}

if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

$output = '
	<div ' . implode( ' ', $wrapper_attributes ) . '>
		' . wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_easl_widget_heading' ) ) . '
		'. $right_menu_html .'
		<div class="'.$container_class.'">
			'. $menu_content .'
			<div class="'. $content_wrapp_class .'">
				<div class="easl-msc-content-wrap-inner">
					'. $parsed_content .'
				</div>
			</div>
		</div>
	</div>
	';

echo $output;