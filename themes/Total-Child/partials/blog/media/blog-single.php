<?php
/**
 * Blog single post standard format media
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 4.9.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Return if there isn't a thumbnail
if ( ! has_post_thumbnail() ) {
	return;
}

$featured_image_link = get_field( 'featured_image_link' );
$open_in_new_tab     = get_field( 'open_in_new_tab' );
?>

<div id="post-media" class="clr">

	<?php
	// Image with lightbox link
	if ( wpex_get_mod( 'blog_post_image_lightbox' ) ) :

		wpex_enqueue_lightbox_scripts(); ?>

        <a href="<?php echo wpex_get_lightbox_image(); ?>" title="<?php esc_attr_e( 'Enlarge Image', 'total' ); ?>" class="wpex-lightbox<?php wpex_entry_image_animation_classes(); ?>"><?php echo wpex_get_blog_post_thumbnail(); ?></a>

	<?php
	// No lightbox
	else : ?>

		<?php

		$thumnail_html = wpex_get_blog_post_thumbnail();
		if ( $thumnail_html ) {
			$open_in_new_tab = $open_in_new_tab ? ' target="blank"' : '';
			if ( $featured_image_link ) {
				echo '<a href="' . esc_url( $featured_image_link ) . '"' . $open_in_new_tab . '>' . $thumnail_html . '</a>';
			} else {
				echo $thumnail_html;
			}
		}
		?>

	<?php endif; ?>

	<?php
	// Blog entry caption
	if ( wpex_get_mod( 'blog_thumbnail_caption' ) && $caption = wpex_featured_image_caption() ) : ?>

        <div class="post-media-caption clr"><?php echo wp_kses_post( $caption ); ?></div>

	<?php endif; ?>

</div><!-- #post-media -->