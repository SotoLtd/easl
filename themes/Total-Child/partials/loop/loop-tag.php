<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


$news_link = trim( wpex_get_custom_permalink() );
if ( ! $news_link ) {
	$news_link = get_permalink();
}

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
			$sources = get_the_term_list( get_the_ID(), EASL_News_Source_Tax::get_slug(), '', ',', '' );
			if ( $sources ):
				?>
                - <span class="easl-news-list-source"><?php echo $sources; ?></span>
			<?php endif; ?>
        </p>
        <h2 class="easl-news-list-title"><a
                    href="<?php echo $news_link; ?>"><?php the_title(); ?></a></h2>
        <div class="easl-news-list-excerpt"><?php wpex_excerpt( array( 'length' => $excerpt_length ) ); ?></div>
    </div>
</article>
