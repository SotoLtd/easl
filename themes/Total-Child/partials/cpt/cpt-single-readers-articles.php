<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

$query_args = array(
    'post_type'      => 'blog',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'post__not_in'   => array( get_the_ID() ),
    'meta_query'     => array(
        'easl_hit_count' => array(
            'key'     => 'easl_hit_count',
            'compare' => 'EXISTS'
        ),
    ),
    'orderby' => array(
        'easl_hit_count' => 'DESC'
    )
);

$blog_query = new WP_Query( $query_args );

if ( $blog_query->have_posts() ):?>
    <div class="wpb_easl_news_list easl-suggested-articles">
        <h2 class="entry-title wpb_easl_news_heading">Other readers articles</h2>
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