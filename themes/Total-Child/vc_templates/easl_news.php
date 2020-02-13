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
 * Shortcode class
 * @var $this SC_LL_Video_Box
 */
$title         = '';
$element_width = '';
$view_all_link = '';
$view_all_url  = '';
$view_all_text = '';
$el_class      = '';
$el_id         = '';
$css_animation = '';
$limit         = '';
$include_categories = '';
$exclude_categories = '';
$excerpt_length = '';

$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$include_categories = $this->string_to_array( $include_categories );
$exclude_categories = $this->string_to_array( $exclude_categories );
$excerpt_length = absint($excerpt_length);

if ( ! $view_all_text ) {
	$view_all_text = 'View all News';
}

if ( $title && $view_all_link ) {
	$title .= '<a class="easl-news-all-link" href="' . esc_url( $view_all_url ) . '">' . $view_all_text . '</a>';
}

$class_to_filter = 'wpb_easl_news_list wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}

if(!$limit){
	$limit = -1;
}
$query_args = array(
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => $limit,
	'meta_query'     => array(
		array(
			'key'     => '_thumbnail_id',
			'compare' => 'EXISTS'
		),
	)
);
$tax_query = array();
if($include_categories) {
	$tax_query[] = array(
		'taxonomy' => 'category',
		'field'    => 'term_id',
		'terms'    => $include_categories,
		'operator' => 'IN',
	);
}
if($exclude_categories) {
	$tax_query[] = array(
		'taxonomy' => 'category',
		'field'    => 'term_id',
		'terms'    => $exclude_categories,
		'operator' => 'NOT IN',
	);
}
if ( count( $tax_query ) > 0 ) {
	$tax_query['relation'] = 'AND';
	$query_args['tax_query'] = $tax_query;
}

$news_query = new WP_Query( $query_args );

if ( $news_query->have_posts() ):
	?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?> class="<?php echo esc_attr( trim( $css_class ) ); ?>">
		<?php echo wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_easl_news_heading' ) ); ?>
        <div class="easl-news-container easl-container">
            <div class="easl-news-row easl-row">
				<?php
				while ( $news_query->have_posts() ) {
					$news_query->the_post();
					if ( ! has_post_thumbnail() ) {
						continue;
					}
					?>
                    <div class="easl-news-col easl-col easl-col-3">
                        <div class="easl-col-inner">
                            <article class="easl-news-item">
                                <figure>
                                    <a href="<?php the_permalink(); ?>">
		                                <?php the_post_thumbnail( 'news_list' ); ?>
                                    </a>
                                </figure>
                                <p class="easl-news-date"><?php echo wpex_date_format( array( 'id'     => get_the_ID(),'format' => 'd M, Y', ) ); ?></p>
                                <h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
                                <div class="eeasl-news-excerpt"><?php echo wpex_get_excerpt( array( 'length' => $excerpt_length ) ); ?></div>
                            </article>
                        </div>
                    </div>
					<?php
				}
				wp_reset_query();
				?>
            </div>
        </div>
    </div>
<?php endif; ?>