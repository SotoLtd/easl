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
 * @var $this EASL_VC_Popular_Blogs
 */
$title          = '';
$view_all_link  = '';
$view_all_url   = '';
$view_all_text  = '';
$el_class       = '';
$el_id          = '';
$css_animation  = '';
$limit          = '';
$show_excerpt   = '';
$excerpt_length = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$excerpt_length = absint( $excerpt_length );

if ( ! $view_all_text ) {
    $view_all_text = 'View all News';
}

if ( $title && $view_all_link ) {
    $title .= '<a class="easl-news-all-link" href="' . esc_url( $view_all_url ) . '">' . $view_all_text . '</a>';
}

$class_to_filter = 'wpb_easl_news_list wpb_content_element ';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->easlGetSettings( 'base' ), $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
$popular_posts_ids = $this->get_popular_blog_ids();

if ( ! $limit ) {
    $limit = - 1;
}
$query_args = array(
    'post_type'      => 'blog',
    'post_status'    => 'publish',
    'posts_per_page' => $limit,
    'post__in'       => $popular_posts_ids,
    'meta_query'     => array(
        array(
            'key'     => '_thumbnail_id',
            'compare' => 'EXISTS'
        ),
    ),
    'orderby'        => 'post__in',
);

$blog_query = new WP_Query( $query_args );


if ( $blog_query->have_posts() ):
    ?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?> class="<?php echo esc_attr( trim( $css_class ) ); ?>">
        <?php echo wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_easl_news_heading' ) ); ?>
        <div class="easl-news-container easl-container">
            <div class="easl-news-row easl-row">
                <?php
                while ( $blog_query->have_posts() ) {
                    $blog_query->the_post();
                    if ( ! has_post_thumbnail() ) {
                        continue;
                    }
                    $news_link = trim( wpex_get_custom_permalink() );
                    if ( ! $news_link ) {
                        $news_link = get_permalink();
                    }
                    $thumbnail = get_field('blog_listing_image', get_the_ID());
                    if(!$thumbnail) {
                        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'news_list');
                    }
                    ?>
                    <div class="easl-news-col easl-col easl-col-3">
                        <div class="easl-col-inner">
                            <article class="easl-news-item easl-blog-item">
                                <figure>
                                    <a href="<?php echo $news_link; ?>">
                                        <img src="<?php echo esc_url($thumbnail); ?>" alt="">
                                    </a>
                                </figure>
                                <p class="easl-news-date">
                                    <?php
                                    echo wpex_date_format( array(
                                        'id'     => get_the_ID(),
                                        'format' => 'd M, Y',
                                    ) );
                                    ?>
                                </p>
                                <h3><a href="<?php echo $news_link; ?>"><?php the_title(); ?></a></h3>
                                <?php if ( $show_excerpt == 'true' ): ?>
                                    <div class="eeasl-news-excerpt">
                                        <?php
                                        echo wpex_get_excerpt( array(
                                            'length'               => $excerpt_length,
                                            'trim_custom_excerpts' => true
                                        ) );
                                        ?>
                                    </div>
                                <?php endif; ?>
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