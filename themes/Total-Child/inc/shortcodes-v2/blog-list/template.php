<?php
/**
 * EASL_VC_News_List
 */
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $el_id
 * @var $this EASL_VC_News_List
 */
$el_class           = '';
$css                = '';
$css_animation      = '';
$limit              = '';
$display_sidebar    = '';
$nl_title           = '';
$nl_limit           = '';
$nl_year            = '';
$include_categories = '';
$exclude_categories = '';
$show_filter        = '';
$excerpt_length     = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$include_categories = $this->string_to_array( $include_categories );
$exclude_categories = $this->string_to_array( $exclude_categories );
$excerpt_length     = intval( $excerpt_length );

$search_req      = ! empty( $_REQUEST['nsearch'] ) ? $_REQUEST['nsearch'] : false;
$news_source_req = ! empty( $_REQUEST['nsource'] ) ? trim( $_REQUEST['nsource'] ) : false;
$year_req        = ! empty( $_REQUEST['nyear'] ) ? absint( $_REQUEST['nyear'] ) : false;

$class_to_filter = 'vcex-module easl-news-list-wrap clr';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();

if ( ! empty( $atts['el_id'] ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $atts['el_id'] ) . '"';
}
if ( $css_class ) {
    $wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}
$limit = absint( $limit );
if ( ! $limit ) {
    $limit = - 1;
}
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
// Build Query
$query_args = array(
    'post_type'      => 'blog',
    'post_status'    => 'publish',
    'posts_per_page' => $limit,
    'paged'          => $paged,
);
$tax_query  = array();
if ( $include_categories ) {
    $tax_query[] = array(
        'taxonomy' => 'category',
        'field'    => 'term_id',
        'terms'    => $include_categories,
        'operator' => 'IN',
    );
}
if ( $exclude_categories ) {
    $tax_query[] = array(
        'taxonomy' => 'blog_category',
        'field'    => 'term_id',
        'terms'    => $exclude_categories,
        'operator' => 'NOT IN',
    );
}

if ( $search_req ) {
    $query_args['s'] = $search_req;
}
if ( $news_source_req ) {
    $tax_query[] = array(
        'taxonomy' => 'blog_category',
        'field'    => 'slug',
        'terms'    => array( $news_source_req ),
    );
}

if ( count( $tax_query ) > 0 ) {
    $tax_query['relation']   = 'AND';
    $query_args['tax_query'] = $tax_query;
}

$news_query = new WP_Query( $query_args );
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <?php if ( $show_filter != 'false' ): ?>
        <div class="easl-news-list-filter">
            <form class="easl-news-list-filter-form" action="" method="get">
                <div class="easl-row">
                    <div class="easl-col easl-col-2">
                        <div class="easl-col-inner">
                            <div class="ec-filter-search">
                                <input type="text" name="nsearch" value="<?php if ( $search_req ) {
                                    echo esc_attr( $search_req );
                                } ?>" placeholder="Search for title or keyword"/>
                                <span class="ecs-icon"><i class="ticon ticon-search" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="easl-col easl-col-2">
                        <div class="easl-col-inner">
                            <div class="easl-custom-select easl-custom-select-filter-source">
                                <span class="ec-cs-label">All Categories</span>
                                <select name="nsource">
                                    <option value="">All Categories</option>
                                    <?php
                                    $news_sources = get_terms( array(
                                        'taxonomy'   => 'blog_category',
                                        'hide_empty' => false,
                                        'orderby'    => 'term_id',
                                        'order'      => 'ASC',
                                        'fields'     => 'all',
                                    ) );
                                    if ( ! is_array( $news_sources ) ) {
                                        $news_sources = array();
                                    }
                                    foreach ( $news_sources as $news_source_term):
                                        ?>
                                        <option value="<?php echo $news_source_term->slug; ?>" <?php selected( $news_source_term->slug, $news_source_req, true ) ?>><?php echo esc_html( $news_source_term->name ); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    <?php endif; ?>
    <div class="easl-news-list-result <?php if ( $news_query->max_num_pages ) {
        echo 'easl-news-list-result-has-pagination';
    } ?>">
        <div class="easl-row">
            <div class="easl-col<?php if ( 'true' == $display_sidebar ) {
                echo ' easl-col-3-4';
            } ?>">
                <div class="easl-col-inner">
                    <div class="easl-news-list-con">
                        <?php
                        if ( $news_query->have_posts() ):
                            while ( $news_query->have_posts() ):
                                $news_query->the_post();
                                $news_link = get_permalink();
                                
                                ?>
                                <article class="easl-news-list-entry <?php if ( has_post_thumbnail() ) {
                                    echo 'easl-news-list-entry-has-thumb';
                                } ?>">
                                    <?php if ( has_post_thumbnail() ): ?>
                                        <div class="easl-news-list-thumb">
                                            <a href="<?php echo $news_link; ?>">
                                                <?php the_post_thumbnail( 'news_list' ); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="easl-news-list-content">
                                        <p class="easl-news-list-meta">
                                            <span class="easl-news-list-date"></span><?php echo wpex_date_format( array(
                                                'id'     => get_the_ID(),
                                                'format' => 'd M, Y',
                                            ) ); ?></span>
                                            <?php
                                            $sources = get_the_term_list( get_the_ID(), 'blog_category', '', ',', '' );
                                            if ( $sources ):
                                                ?>
                                                - <span class="easl-news-list-source"><?php echo $sources; ?></span>
                                            <?php endif; ?>
                                        </p>
                                        <h2 class="easl-news-list-title"><a
                                                    href="<?php echo $news_link; ?>"><?php the_title(); ?></a></h2>
                                        <div class="easl-news-list-excerpt"><?php wpex_excerpt( array( 'length' => $excerpt_length, 'trim_custom_excerpts' => true ) ); ?></div>
                                    </div>
                                </article>
                            <?php
                            endwhile;
                            wp_reset_query();
                        else:
                            echo '<div class="easl-not-found"><p>' . __( 'Nothing has been found', 'total-child' ) . '</p></div>';
                        endif;
                        ?>
                    </div>
                    <?php if ( $news_query->max_num_pages ): ?>
                        <div class="easl-news-list-pagination easl-list-pagination">
                            <?php echo paginate_links( array(
                                'total'     => $news_query->max_num_pages,
                                'current'   => $paged,
                                'end_size'  => 3,
                                'mid_size'  => 5,
                                'prev_next' => true,
                                'prev_text' => '<span class="ticon ticon-angle-left" aria-hidden="true"></span>',
                                'next_text' => '<span class="ticon ticon-angle-right" aria-hidden="true"></span>',
                                'type'      => 'list',
                            ) ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ( 'true' == $display_sidebar ): ?>
                <div class="easl-col easl-col-4 easl-news-list-newsletters">
                    <div class="easl-col-inner">
                        <?php dynamic_sidebar( 'blog-sidebar ' ); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>