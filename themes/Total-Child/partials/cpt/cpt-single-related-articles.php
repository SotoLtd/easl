<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

$categories = wp_get_post_terms( get_the_ID(), 'blog_category', array( 'fields' => 'ids' ) );
$tags       = wp_get_post_terms( get_the_ID(), 'blog_tag', array( 'fields' => 'ids' ) );
$query_args = array(
    'post_type'      => 'blog',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'post__not_in'   => array( get_the_ID() ),
    'meta_query'     => array(
        array(
            'key'     => '_thumbnail_id',
            'compare' => 'EXISTS'
        ),
    ),
);
$tax_query  = array();
if ( $categories ) {
    $tax_query[] = array(
        'taxonomy' => 'blog_category',
        'field'    => 'term_id',
        'terms'    => $categories,
        'operator' => 'IN',
    );
}
if ( $tags ) {
    $tax_query[] = array(
        'taxonomy' => 'blog_tag',
        'field'    => 'term_id',
        'terms'    => $tags,
        'operator' => 'IN',
    );
}
if ( count( $tax_query ) > 0 ) {
    $tax_query['relation']   = 'OR';
    $query_args['tax_query'] = $tax_query;
}
$blog_query = new WP_Query( $query_args );

if ( $blog_query->have_posts() ):?>
    <div class="wpb_easl_news_list easl-suggested-articles">
        <h2 class="entry-title wpb_easl_news_heading">Related articles</h2>
        <div class="easl-news-row easl-row">
            <?php
            while ( $blog_query->have_posts() ):
                $blog_query->the_post();
                $article_id = get_the_ID();
    
                $thumbnail = get_field('blog_listing_image', get_the_ID());
                if(!$thumbnail) {
                    $thumbnail = get_the_post_thumbnail_url($article_id, 'news_list');
                }
                ?>
                <div class="easl-news-col easl-col easl-col-3">
                    <div class="easl-col-inner">
                        <article class="easl-news-item">
                            <figure>
                                <a href="<?php echo get_permalink( $article_id ) ?>">
                                    <img src="<?php echo esc_url($thumbnail); ?>" alt="">
                                </a>
                            </figure>
                            <p class="easl-news-date"><?php echo wpex_date_format( array(
                                    'id'     => $article_id,
                                    'format' => 'd M, Y',
                                ) ); ?></p>
                            <h3>
                                <a href="<?php echo get_permalink( $article_id ) ?>"><?php echo get_the_title( $article_id ); ?></a>
                            </h3>
                        </article>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_query(); ?>
        </div>
    </div>
<?php endif; ?>