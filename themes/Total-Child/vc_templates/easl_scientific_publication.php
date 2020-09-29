<?php
if (!defined('ABSPATH')) {
    die('-1');
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class
 * @var $this EASL_VC_Scientific_Publication
 */

$title = $element_width = $view_all_link = $view_all_url = $view_all_text = $el_class = $el_id = $css_animation = $css = '';
$posts_per_page = $pagination = $order = $orderby = '';
$enable_related_links = $relink_title = $related_links = '';
$hide_topic = $include_categories = '';
$deny_detail_page = '';
$hide_excerpt = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = 'wpb_easl_scientific_publication wpb_content_element';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class		 = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings[ 'base' ], $atts );
$wrapper_attributes = array();
if (!empty($el_id)) {
    $wrapper_attributes[] = 'id="' . esc_attr($el_id) . '"';
}
if ( $css_class ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

wp_enqueue_style('easl-scientific-publication-style',
    get_stylesheet_directory_uri() . '/assets/css/easl_scientific_publication.css');

wp_enqueue_script('easl-scientific-publication-script',
    get_stylesheet_directory_uri() . '/assets/js/easl_scientific_publication.js',
    ['jquery'],
    '1.2',
    true);

$has_filter = false;
$filter_ec_filter_topics = [];
$filter_ecf_search = '';
$filter_ecf_year = '';

if(isset($_REQUEST['ec_filter_topics'])){
    $filter_ec_filter_topics = $_REQUEST['ec_filter_topics'];
    $has_filter = true;
}
if(isset($_REQUEST['ecf_search']) && ($_REQUEST['ecf_search'] != '') ){
    $filter_ecf_search = $_REQUEST['ecf_search'];
    $has_filter = true;
}
if(!empty($_REQUEST['ecf_year'])){
    $filter_ecf_year = $_REQUEST['ecf_year'];
    $has_filter = true;
}


if (!$view_all_text) {
    $view_all_text = 'View all Events';
}

if ($title && $view_all_link) {
    $title .= '<a class="easl-events-all-link" href="' . esc_url($view_all_url) . '">' . $view_all_text . '</a>';
}
$related_links_data = array();
if($enable_related_links){
	$related_links_data = $this->get_related_links_data($related_links);
}

$filter_by_topic = '';
$taxonomy_string = '';
$is_custom_topic = false;
if($hide_topic === "false"){
    $taxonomies = get_categories(['taxonomy' => 'publication_topic']);
	$taxonomies = get_terms( array(
		'taxonomy' => Publication_Config::get_topic_slug(),
		'hide_empty' => true,
		'orderby' => 'name',
		'order' => 'ASC',
	) );
    if($taxonomies){
        foreach ($taxonomies as $taxonomy){
            $bg_color ='';
            $topic_color = get_term_meta($taxonomy->term_id, 'easl_tax_color', true);
            if(!$topic_color) {
                $bg_color = 'blue';
            } else {
                $bg_color = $topic_color;
            }
            $filter_topic_checked = '';
            if(in_array($taxonomy->term_id, $filter_ec_filter_topics)){
                $filter_topic_checked = 'checked';
                $is_custom_topic = true;
            }
            $taxonomy_string .= '<li>'.
                    '<label class="easl-custom-checkbox csic-'.$bg_color.'">'.
                        '<input type="checkbox" name="ec_filter_topics[]" value="'.$taxonomy->term_id.'" data-countries="" '.$filter_topic_checked.'> <span>'.$taxonomy->name.'</span>'.
                    '</label>'.
                '</li>';
        }
    }

    $filter_by_topic = '<div class="wpb_column vc_column_container vc_col-sm-4">'.
                            '<div class="vc_column-inner ">'.
                                '<div class="wpb_wrapper">'.
                                    '<div class="wpb_raw_code wpb_content_element wpb_raw_html">'.
                                        '<div class="wpb_wrapper">'.
                                            '<div class="easl-col-inner">'.
                                                '<div class="easl-col-inner">'.

                                                    '<h4 style="font-size: 21px;border-bottom: 1px solid #d7d7d7;">Show me:</h4>'.
                                                    '<ul class="ec-filter-topics">'.
                                                        '<li>'.
                                                            '<label class="easl-custom-checkbox easl-cb-all csic-light-blue easl-active">'.
                                                                '<input type="checkbox" name="ec_filter_topics[]" value="" '.(!$is_custom_topic ? 'checked="checked"' : '').'> <span>All topics</span>'.
                                                            '</label>'.
                                                        '</li>'.
                                                        $taxonomy_string.
                                                    '</ul>'.
                                                '</div>'.
                                            '</div>'.
                                        '</div>'.
                                    '</div>'.
                                '</div>'.
                            '</div>'.
                        '</div>';

    $br = '';
    $no_bottom_margins = '';
}  else {
    $br = '<br>';
    $no_bottom_margins = 'no-bottom-margins';
}

$current_year = (new DateTime())->format('Y');
$the_year = 2010;
$option = '';

$years_dd = $this->get_years($include_categories, $filter_ec_filter_topics);
$filter_ecf_year = absint($filter_ecf_year);
foreach ( $years_dd as $year ){
    if(!$year){
        continue;
    }
	$option .= '<option value="' . $year . '" ' . selected( $year, $filter_ecf_year, false )  . '>' . $year . '</option>';
}
$related_links_html = '';
if($enable_related_links){
	$related_links_html .= '<div class="easl-sp-related-links" style="padding-bottom: 7px;">';
	$related_links_html .= '<h4 style="font-size: 18px;margin-bottom: 18px;">'. $relink_title .'</h4>';
	foreach($related_links_data as $rel_link) {
		$link_attributes = array();
		$link_attributes[] = 'href="' . trim( $rel_link['url'] ) . '"';
		if ( ! empty( $rel_link['target'] ) ) {
			$link_attributes[] = 'target="' . esc_attr( trim( $rel_link['target'] ) ) . '"';
		}
		if ( ! empty( $rel_link['rel'] ) ) {
			$link_attributes[] = 'rel="' . esc_attr( trim( $rel_link['rel'] ) ) . '"';
		}
		$link_attributes = implode( ' ', $link_attributes );
		$related_links_html .= '<a class="animate-on-hover wpex-dhover-0 publication-filter-button" '. $link_attributes .' href="'. esc_url($rel_link['url']) .'">'. $rel_link['title'] .'<span class="vcex-icon-wrap theme-button-icon-right"><span class="ticon ticon-angle-right"></span></span></span></a>' . $br;
	}
	$related_links_html .= '</div>';
}

$top_filter = '<div class="vc_row wpb_row '.$no_bottom_margins.' vc_inner vc_row-fluid easl-scientific-publication-container">'.
    $filter_by_topic.
	'<div class="wpb_column vc_column_container vc_col-sm-8">'.
		'<div class="vc_column-inner ">'.
			'<div class="wpb_wrapper">'.
				'<div class="wpb_raw_code wpb_content_element wpb_raw_html">'.
					'<div class="wpb_wrapper">'.
						'<div class="easl-col-inner" >'.
							'<div class="ec-filter-search">'.
								'<input type="text" name="ecf_search" value="'.($filter_ecf_search ? $filter_ecf_search : '').'" placeholder="Search for publication"/>'.
								'<span class="ecs-icon"><i class="ticon ticon-search" aria-hidden="true"></i></span>'.
							'</div>'.
							'<h4 style="font-size: 21px">Filter Publications:</h4>'.

							'<div class="easl-custom-select" style="margin-bottom: 15px;">'.
								'<span class="ec-cs-label">Select a year</span>'.
								'<select name="ecf_year" placeholder="Select a year">'.
									'<option value="">Select a year</option>'.
                                    $option.
								'</select>'.
							'</div>';

$top_filter .= $hide_topic === "false" ? $related_links_html : '';
$top_filter .=			'</div>'.
					'</div>'.
				'</div>'.
			'</div>'.
		'</div>'.
	'</div>';
$top_filter .=  $hide_topic === "true" ? '<div class="wpb_column vc_column_container vc_col-sm-4" style="border-left: 1px solid #104f85; margin-bottom: 15px;">'.
'<div class="vc_column-inner ">'.
			'<div class="wpb_wrapper">'.
				'<div class="wpb_raw_code wpb_content_element wpb_raw_html">'.
					'<div class="wpb_wrapper">'.
						'<div class="easl-col-inner" >'. $related_links_html.'</div></div></div></div></div></div>': '';
$top_filter .= '</div>';

$posts_per_page = absint( $posts_per_page );
if ( ! $posts_per_page ) {
	$posts_per_page = - 1;
}
if ( ! in_array( $order, array( 'ASC', 'DESC' ) ) ) {
	$order = 'DESC';
}
if ( ! in_array( $orderby, array( 'title', 'ID' ) ) ) {
	$orderby = 'pub_date';
}

if ( $include_categories ) {
	$include_categories = explode( ',', $include_categories );
}
if ( ! $include_categories ) {
	$include_categories = array();
}

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$query_args = array(
	'post_type'      => Publication_Config::get_publication_slug(),
	'post_status'    => 'publish',
	'posts_per_page' => $posts_per_page,
	'paged'          => $paged,
	'order'          => $order,
);
if ( $orderby == 'pub_date' ) {
	$query_args['orderby']  = 'meta_value_num';
	$query_args['meta_key'] = 'publication_raw_date';
} else {
	$query_args['orderby'] = $orderby;
}
$pub_tag_query = array();
if ( count( $include_categories ) > 0 ) {
	$pub_tag_query[] = array(
		'taxonomy' => 'publication_category',
		'field'    => 'id',
		'terms'    => $include_categories,
	);
}
if ( is_array( $filter_ec_filter_topics ) && count( $filter_ec_filter_topics ) > 0 && $filter_ec_filter_topics[0] != '' ) {
	$pub_tag_query[] = array(
		'taxonomy' => 'publication_topic',
		'field'    => 'id',
		'terms'    => $filter_ec_filter_topics,
		'operator' => 'IN',
	);
}
if ( count( $pub_tag_query ) > 0 ) {
	$pub_tag_query['relation'] = 'AND';
	$query_args['tax_query']   = $pub_tag_query;
}

if ( $filter_ecf_search ) {
	$query_args['s'] = $filter_ecf_search;
}
if ( $filter_ecf_year ) {
	$query_args['meta_query'] = array(
		'relation'         => 'AND',
        array(
			'key'     => 'publication_raw_date',
			'value'   => $filter_ecf_year . '0101',
			'compare' => '>=',
		),
        array(
			'key'     => 'publication_raw_date',
			'value'   => $filter_ecf_year . '1231',
			'compare' => '<=',
		),
	);
}


//$css_animation = $this->getCSSAnimation($css_animation);

$easl_query = new WP_Query( $query_args );

$topic_label = 'Topic:';
$topic_delimiter = ' | ';
$arrow_style = wpex_get_mod( 'pagination_arrow' );
$arrow_style = $arrow_style ? esc_attr( $arrow_style ) : 'angle';

// Arrows with RTL support
$prev_arrow = is_rtl() ? 'ticon ticon-' . $arrow_style . '-right' : 'ticon ticon-' . $arrow_style . '-left';
$next_arrow = is_rtl() ? 'ticon ticon-' . $arrow_style . '-left' : 'ticon ticon-' . $arrow_style . '-right';
// Previous text
$prev_text = '<span class="' . $prev_arrow . '" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Previous', 'total' ) . '</span>';
// Next text
$next_text = '<span class="' . $next_arrow . '" aria-hidden="true"></span><span class="screen-reader-text">' . esc_html__( 'Next', 'total' ) . '</span>';
$big = 999999999;
$args = array(
    'base'               => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
    'format'             => '?paged=%#%',
    'total'              => $easl_query->max_num_pages,
    'current'            => $paged,
    'show_all'           => false,
    'end_size'           => 1,
    'mid_size'           => 2,
    'prev_next'          => true,
    'prev_text'          => $prev_text,
    'next_text'          => $next_text,
    'type'               => 'list',
    'add_args'           => false,
);

$pagination = '<div class="easl-ec-pagination-container" style="display: flex">' . paginate_links($args) . '</div>';

$not_found_text = $has_filter ? 'Nothing has been found' : 'content is coming soon';
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
	<?php if($title){  wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_easl_widget_heading')); } ?>
	<div class="easl-scientific-publication-wrap">
		<?php if($top_filter): ?>
		<form class="publication-filter" action="" method="get" style="background: #fff; border: 3px solid #004b87; padding: 30px 15px; margin-bottom: 30px;">
			<?php echo $top_filter; ?>
		</form>
		<?php endif; ?>
		<?php echo $pagination; ?>
		<div class="easl-scientific-publication-inner">
			<?php
			if ( $easl_query->have_posts() ){
				while ( $easl_query->have_posts() ):
					$easl_query->the_post();
					$topics = '';
					$topics = easl_publications_topics_name(get_the_ID(), false, ' - ');
					if($hide_topic === "true"){
						$topics = '';
						$topic_label = '';
						$topic_delimiter = '';
					}
					$image = has_post_thumbnail( get_the_ID() ) ?
						wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
					$image_src = $image ? $image[0] : '';


					$excerpt = $hide_excerpt === "true" ? '' : get_the_excerpt();
					$read_more_link =  $deny_detail_page === "true" ? get_field('link_to_journal_hepatology') : get_permalink();
					$target = $deny_detail_page === "true" ? 'target="_blank"' : '';
					$publication_date = get_field('publication_raw_date');
					$publication_date_format = get_field('publication_date_format');
					$custom_date_text = get_field('custom_date_text');
					if(!$publication_date_format){
						$publication_date_format = 'Y';
                    }
					if($publication_date_format == 'custom'){
						$publication_date = $custom_date_text;
                    }elseif($publication_date){
						$publication_date = DateTime::createFromFormat('d/m/Y', $publication_date);
						$publication_date = $publication_date->format($publication_date_format);
                    }
                    $logged_in = easl_mz_is_member_logged_in();
                    $cpg = has_term('clinical-practice-guidelines', 'publication_category', get_the_ID());
                    $needs_modal = !$logged_in && $cpg;

					?>
					<article class="scientific-publication <?php if(!$image_src){echo 'sp-has-no-thumb';} ?> easl-sprow-color-<?php echo easl_get_publication_topic_color(); ?> clr">
						<?php if($image_src): ?>
						<div class="sp-thumb">
							<a href="<?php echo $read_more_link;?>" title="" <?php $target ?> class="<?php if ($needs_modal):?>sp-modal-trigger<?php endif;?>" >
								<img alt="" src="<?php echo $image_src; ?>"/>
							</a>
						</div>
						<?php endif; ?>
						<div class="scientific-publication-content  sp-content">
							<div class="sp-item-meta-title">
								<p class="sp-meta">
									<?php if($publication_date): ?>
									<span class="sp-meta-date"><?php echo $publication_date; ?></span>
									<?php endif; ?>
									<?php if($topic_delimiter): ?>
									<span class=sp-meta-sep"><?php echo $topic_delimiter; ?></span>
									<?php endif; ?>
									<?php if($topics): ?>
									<span class="sp-meta-type"><?php echo $topic_label; ?></span>
									<span class="sp-meta-value"><?php echo $topics; ?></span>
									<?php endif; ?>
								</p>
								<h3><a href="<?php echo $read_more_link; ?>" <?php echo $target; ?> class="<?php if ($needs_modal):?>sp-modal-trigger<?php endif;?>"><?php the_title(); ?></a></h3>
							</div>
							<p class="sp-excerpt"><?php echo $excerpt; ?></p>
                            <a class="easl-button<?php if ($needs_modal):?> sp-modal-trigger<?php endif;?>" href="<?php echo $read_more_link; ?>" <?php echo $target; ?>><?php _e('Read More', 'total-child'); ?> <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
						</div>
					</article>
			<?php
				endwhile;
				wp_reset_query();

			}else{
			?>
			<div class="easl-no-scientific-publication"><?php echo $not_found_text; ?></div>
			<?php } ?>
		</div>
		<?php echo $pagination; ?>
	</div>
</div>
<?php require_once(__DIR__ . '/../partials/publication/publication-modal.php');