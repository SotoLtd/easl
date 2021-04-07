<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}
$articles = get_field( 'suggested_articles' );
if ( ! empty( $articles ) ):?>
    <div class="wpb_easl_news_list easl-suggested-articles">
        <h2 class="entry-title wpb_easl_news_heading">Suggested articles</h2>
        <div class="easl-news-row easl-row">
            <?php foreach ( $articles as $article_id ):
    
                $thumbnail = get_field('blog_listing_image', get_the_ID());
                if(!$thumbnail) {
                    $thumbnail = get_the_post_thumbnail_url($article_id, 'news_list');
                }
                ?>
                <div class="easl-news-col easl-col easl-col-3">
                    <div class="easl-col-inner">
                        <article class="easl-news-item easl-blog-item">
                            <figure>
                                <a href="<?php echo get_permalink( $article_id ) ?>">
                                    <img src="<?php echo esc_url($thumbnail); ?>" alt="">
                                </a>
                            </figure>
                            <p class="easl-news-date"><?php echo wpex_date_format( array( 'id'     => $article_id,
                                                                                          'format' => 'd M, Y',
                                ) ); ?></p>
                            <h3>
                                <a href="<?php echo get_permalink( $article_id ) ?>"><?php echo get_the_title( $article_id ); ?></a>
                            </h3>
                        </article>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>