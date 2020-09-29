<?php
/**
 * The page header displays at the top of all single pages, posts and archives.
 *
 * @see framework/page-header.php for all page header related functions.
 * @see framework/hooks/actions.php for all functions attached to the header hooks.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 3.6.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( is_singular( 'event' ) && easl_is_event_template_format( 'small-event' ) ) {
	get_template_part('partials/event/small-event/header');
} else {
	$post_title_icon       = get_post_meta( wpex_get_current_post_id(), 'ese_post_title_icon', true );
	$post_title_icon_class = '';
	if ( $post_title_icon ) {
		$post_title_icon_class = 'page-hedaer-has-title-icon page-header-title-icon-' . $post_title_icon;
	}

	?>
	<?php wpex_hook_page_header_before(); ?>

    <header class="<?php echo wpex_page_header_classes(); ?>">
		<?php
		if ( ( 'background-image' == wpex_page_header_style() ) && wpex_page_header_background_image() ) {
			echo easl_page_header_background_image();
		} else {
			echo easl_singular_default_header_background_image();
		}
		?>
		<?php wpex_hook_page_header_top(); ?>

        <div class="page-header-inner container <?php echo $post_title_icon_class; ?> clr">
			<?php wpex_hook_page_header_inner(); // All default content added via this hook
			?>
        </div><!-- .page-header-inner -->

		<?php wpex_hook_page_header_bottom(); ?>

    </header><!-- .page-header -->

	<?php

	wpex_hook_page_header_after();
}
?>
