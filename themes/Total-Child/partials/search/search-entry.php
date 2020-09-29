<?php
/**
 * Search entry layout
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if post has thumbnail
$has_thumb = apply_filters( 'wpex_search_has_post_thumbnail', has_post_thumbnail() );

// Add classes to the post_class
$classes   = array();
$classes[] = 'search-entry';
$classes[] = 'clr';
if ( ! $has_thumb ) {
	$classes[] = 'search-entry-no-thumb';
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<?php
	if ( get_post_type() == EASL_Event_Config::get_event_slug() ) {
		get_template_part( 'partials/event/event-loop' );
	} elseif ( get_post_type() == Publication_Config::get_publication_slug() ) {
		$topics         = easl_publications_topics_name( get_the_ID(), false, ' - ' );
		$image          = has_post_thumbnail( get_the_ID() ) ? wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' ) : '';
		$image_src      = $image ? $image[0] : '';
		$excerpt        = get_the_excerpt();
		$read_more_link = trim( get_field( 'link_to_journal_hepatology' ) );
		$target         = ' target="_blank"';
		if ( ! $read_more_link ) {
			$read_more_link = get_permalink();
			$target         = '';
		}
		$publication_date = get_field( 'publication_date' );
		?>
        <div class="scientific-publication <?php if ( ! $image_src ) {
			echo 'sp-has-no-thumb';
		} ?> easl-sprow-color-<?php echo easl_get_publication_topic_color(); ?> clr">
			<?php if ( $image_src ): ?>
                <div class="sp-thumb">
                    <a href="<?php echo $read_more_link; ?>" title=""<?php $target; ?>>
                        <img alt="" src="<?php echo $image_src; ?>"/>
                    </a>
                </div>
			<?php endif; ?>
            <div class="scientific-publication-content  sp-content">
                <div class="sp-item-meta-title">
                    <p class="sp-meta">
						<?php if ( $publication_date ): ?>
                            <span class="sp-meta-date"><?php echo $publication_date; ?></span>
						<?php endif; ?>
                        <?php if($publication_date && $topics): ?>
                        <span class=sp-meta-sep"> | </span>
                        <?php endif; ?>
						<?php if ( $topics ): ?>
                            <span class="sp-meta-type"><?php _e( 'Topic:', 'total-child' ); ?></span>
                            <span class="sp-meta-value"><?php echo $topics; ?></span>
						<?php endif; ?>
                    </p>
                    <h3><a href="<?php echo $read_more_link; ?>" <?php echo $target; ?>><?php the_title(); ?></a></h3>
                </div>
				<?php if ( has_excerpt() ): ?>
                    <p class="sp-excerpt"><?php the_excerpt(); ?></p>
				<?php endif; ?>
                <a class="easl-button" href="<?php echo $read_more_link; ?>" <?php echo $target; ?>><?php _e('Read More', 'total-child'); ?> <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
            </div>
        </div>
    <?php } else { ?>
		<?php if ( $has_thumb ) : ?>
			<?php get_template_part( 'partials/search/search-entry-thumbnail' ); ?>
		<?php endif; ?>
        <div class="search-entry-text"><?php

			// Display header
			get_template_part( 'partials/search/search-entry-header' );

			// Display excerpt
			get_template_part( 'partials/search/search-entry-excerpt' );

			?></div>
	<?php } ?>
</article>