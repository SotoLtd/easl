<?php
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
 * @var $this EASL_VC_Yearly_Awardee
 */
$el_id            = '';
$el_class         = '';
$css              = '';
$css_animation    = '';
$people_per_row   = '';
$award_title_type = '';
$display_thumb    = '';
$query_type       = '';
$include_awards   = '';
$award_type       = '';
$year_num         = '';
$past_year_only   = '';
$people_order     = '';
$people_orderby   = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = 'easl-yearly-awardees-wrap clr';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

if ( ! empty( $atts['el_id'] ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

if ( ! in_array( $people_order, array( 'ASC', 'DESC' ) ) ) {
	$people_order = 'ASC';
}

$peoples_args = array(
	'post_type'      => 'staff',
	'posts_per_page' => - 1,
	'order'          => $people_order,
);
switch ( $people_orderby ) {
	case 'first_name':
		$peoples_args['orderby']  = 'meta_value';
		$peoples_args['meta_key'] = 'first_name';
		break;
	case 'last_name':
		$peoples_args['orderby']  = 'meta_value';
		$peoples_args['meta_key'] = 'last_name';
		break;
	case 'ID':
		$peoples_args['orderby'] = 'ID';
		break;
	case 'title':
		$peoples_args['orderby'] = 'title';
		break;
	case 'menu_order':
		$peoples_args['orderby'] = 'menu_order';
		break;

	default:
		$peoples_args['orderby'] = 'post__in';
}
$people_col_width = '';
switch ( $people_per_row ) {
	case '1':
		$people_col_width = 'vc_col-sm-12';
		break;
	case '2':
		$people_col_width = 'vc_col-sm-6';
		break;
	case '3':
		$people_col_width = 'vc_col-sm-4';
		break;
	case '4':
		$people_col_width = 'vc_col-sm-3';
		break;
	default:
		$people_col_width = 'vc_col-sm-4';
}

$award_type = absint( $award_type );

$year_num       = absint( $year_num );
$past_year_only = 'true' == $past_year_only;

$avaiable_years = array();
if ( $year_num > 0 ) {
	$avaiable_years = EASL_Award_Config::get_years( $award_type, $year_num, $past_year_only );
}
$do_auery   = true;
$query_args = array(
	'post_type'      => EASL_Award_Config::get_slug(),
	'posts_per_page' => - 1,
);

if ( $query_type == 'include' ) {
	$include_awards         = $this->string_to_array( $include_awards );
	$query_args['post__in'] = $include_awards;
	$query_args['orderby']  = 'post__in';
} else {
	$query_args['order']    = 'DESC';
	$query_args['oderby']   = 'meta_value_num';
	$query_args['meta_key'] = 'award_year';


	if ( count( $avaiable_years ) > 0 ) {
		$query_args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key'     => 'award_year',
				'value'   => $avaiable_years,
				'compare' => 'IN',
			)
		);
	} elseif ( $past_year_only ) {
		$query_args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'key'     => 'award_year',
				'value'   => date( "Y" ),
				'compare' => '<',
			)
		);
	}
	if ( $award_type ) {
		$query_args['tax_query'] = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'award_group',
				'field'    => 'id',
				'terms'    => array( $award_type ),
				'operator' => 'IN',
			)
		);
	}

	if ( $year_num > 0 && count( $avaiable_years ) < 1 ) {
		$do_auery = false;
	}
}
$award_query = false;
if ( $do_auery ) {
	$award_query = new WP_Query( $query_args );
}
if ( $award_query && $award_query->have_posts() ):
	?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php
		while ( $award_query->have_posts() ) {
			$award_query->the_post();
			include locate_template( 'partials/award/year-row.php' );
		}
		?>
    </div>
	<?php
	wp_reset_query();
endif;
?>