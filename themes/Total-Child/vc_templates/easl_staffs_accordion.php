<?php
/**
 * EASL_VC_Staffs
 */
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $el_class
 * @var $el_id
 * @var $this EASL_VC_Staffs_Accordion
 */
$el_class              = '';
$css                   = '';
$css_animation         = '';
$widget_title          = '';
$query_type            = '';
$include_stuffs        = '';
$staffs_number         = '';
$include_categories    = '';
$cat_relation          = '';
$order                 = '';
$orderby               = '';
$show_country_in_title = '';
$show_title            = '';
$show_thumb            = '';
$show_bio              = '';


$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$show_country_in_title = $show_country_in_title == 'true';
$show_title            = $show_title == 'true';
$show_thumb            = $show_thumb == 'true';
$show_bio              = $show_bio == 'true';

$class_to_filter = 'vcex-module easl-staffs-accordion-wrap clr';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();

if ( $widget_title && empty( $atts['el_id'] ) ) {
	$atts['el_id'] = sanitize_title_with_dashes( $widget_title );
}

if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
// Build Query
$query_args = array(
	'post_type'      => 'staff',
	'posts_per_page' => - 1,
);

if ( $query_type == 'include' ) {
	$include_stuffs         = $this->string_to_array( $include_stuffs );
	$query_args['post__in'] = $include_stuffs;
	$query_args['orderby']  = 'post__in';
} else {
	$staffs_number = absint( $staffs_number );
	if ( $staffs_number ) {
		$query_args['posts_per_page'] = $staffs_number;
	}
	$order = strtoupper( $order );
	if ( in_array( $order, array( 'ASC', 'DESC' ) ) ) {
		$query_args['order'] = $order;
	}
	if ( $orderby && in_array( $orderby, array( 'date', 'title', 'ID', 'menu_order' ) ) ) {
		$query_args['orderby'] = $orderby;
	}

	$cats_query = $this->build_category_query( $include_categories, $cat_relation );

	if ( $cats_query ) {
		$query_args['tax_query'] = $cats_query;
	}
}

$staff_query             = new WP_Query( $query_args );
$vc_accordion_sc_content = '';
if ( $staff_query->have_posts() ) {
	$vc_accordion_sc_content = '[vc_tta_accordion c_icon="chevron" c_position="right" collapsible_all="true" active_section="9999" el_class="easl-staff-vc-accordion"]';
	while ( $staff_query->have_posts() ) {
		$staff_query->the_post();
		/**
		 * @todo Build the title from name parts
		 */
		$post = get_post();
		$staff_title     = get_the_title();
		$country         = trim( get_field( 'country' ) );
		$accordion_title = $staff_title;
		if ( $show_country_in_title && $country ) {
			$accordion_title .= ', ' . $country;
		}
		$vc_accordion_sc_content .= '[vc_tta_section title="' . htmlspecialchars( $accordion_title, ENT_QUOTES ) . '" tab_id="' . sanitize_html_class( sanitize_title_with_dashes( $accordion_title ) ) . '"]';
		if ( $show_title ) {
			$vc_accordion_sc_content .= '<h3 class="easl-staff-accordiaon-staff-title">' . $staff_title . '</h3>';
		}
		if ( $show_thumb || $show_bio ) {
			$vc_accordion_sc_content .= '<div class="easl-staff-accordion-staff-bio clr">';
		}
		if ( $show_thumb && has_post_thumbnail() ) {
			$vc_accordion_sc_content .= $this->get_staff_profile_thumb( get_the_ID() );
		}
		if ( $show_bio && $this->staff_has_details( get_the_ID() ) ) {
			$vc_accordion_sc_content .= wpb_js_remove_wpautop($post->post_content, true);
		}
		if ( $show_thumb || $show_bio ) {
			$vc_accordion_sc_content .= '</div>';
		}

		$vc_accordion_sc_content .= '[/vc_tta_section]';
	}
	$vc_accordion_sc_content .= '[/vc_tta_accordion]';
	wp_reset_query();
}
if ( $vc_accordion_sc_content ):
	?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php if ( $widget_title ): ?>
            <div class="easl-staffs-accordion-widget-title">
                <h2><?php echo esc_html( $widget_title ); ?></h2>
            </div>
		<?php endif; ?>
		<?php echo wpb_js_remove_wpautop( $vc_accordion_sc_content, false ); ?>
    </div>
<?php endif; ?>
