<?php
/**
 * EASL_VC_Staffs
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $el_class
 * @var $el_id
 * @var $this EASL_VC_Membership_Categories
 */
$title ='';
$el_class      = '';
$css           = '';
$css_animation = '';
$order         = '';
$orderby       = '';
$limit         = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = 'easl-membership-category-wrap';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();

if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
// Build Query
$limit = absint( $limit );
if ( ! $limit ) {
	$limit = - 1;
}
if ( !in_array( $order, array( 'ASC', 'DESC' ) ) ) {
	$order = 'DESC';
}
$query_args = array(
	'post_type'      => EASL_Membership_Category_Config::get_slug(),
	'status'         => 'publish',
	'posts_per_page' => $limit,
	'order'          => $order,
);

if ( $orderby == 'title' ) {
	$query_args['orderby']  = 'title';
} elseif ( $orderby == 'menu_order' ) {
	$query_args['orderby']  = 'menu_order';
}

$mc_query = new WP_Query( $query_args );
$html = '';
if ( $mc_query->have_posts() ) {
	$html = '[vc_tta_tour style="easl-flat" active_section="1"]';
	while ( $mc_query->have_posts() ){
		$mc_query->the_post();
		$post = get_post();
		$become_member_link = get_field('become_a_member_link');
		$renew_membership_link = get_field('renew_membership_link');
		$html .= '[vc_tta_section title="'. htmlspecialchars(get_the_title(), ENT_QUOTES) .'" tab_id="'. sanitize_html_class(strtolower(get_the_title())) .'"]';
		if(has_post_thumbnail()){
			$html .= '<div class="easl-row">';
			$html .= '<div class="easl-col easl-col-2">';
			$html .= '<div class="easl-col-inner">';
		}
		$html .= '<div class="easl-membership-category-content">' . wpb_js_remove_wpautop($post->post_content, true) . '</div>';
		if($become_member_link || $renew_membership_link){
			$html .= '<div class="easl-generic-buttons-wrap easl-align-left">';
			if($become_member_link) {
				$html .= '<a class="easl-generic-button easl-color-lightblue easl-size-small easl-align-inline" target="_blank" href="' . esc_url($become_member_link) . '">'. __('Become a Member', 'total-child') .'<span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>';
			}
			if($renew_membership_link) {
				$html .= '<a class="easl-generic-button easl-color-lightblue easl-size-small easl-align-inline" target="_blank" href="' . esc_url($renew_membership_link) . '">'. __('Renew Membership', 'total-child') .'<span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>';
			}
			$html .= '</div>';
		}
		if(has_post_thumbnail()){
			$html .= '</div>';
			$html .= '</div>';
			$html .= '<div class="easl-col easl-col-2">';
			$html .= '<div class="easl-col-inner">';
			$html .= get_the_post_thumbnail();
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '[/vc_tta_section]';
	}
	$html .= '[/vc_tta_tour]';
	wp_reset_query();
}
if ( $html ): ?>
	<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php if($title){echo wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_easl_widget_heading' ) );} ?>
		<?php echo wpb_js_remove_wpautop($html, false); ?>
	</div>
<?php endif;?>