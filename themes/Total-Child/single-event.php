<?php
/**
 * The template for displaying all pages, single posts and attachments
 *
 * @package Total WordPress Theme
 * @subpackage Templates
 * @version 4.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
remove_action( 'wpex_hook_main_bottom', 'wpex_next_prev', 10 );
get_header(); ?>

	<div id="content-wrap" class="container clr">

		<?php wpex_hook_primary_before(); ?>

		<div id="primary" class="clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content clr">

				<?php wpex_hook_content_top(); ?>

				<?php
				// Start loop
                while ( have_posts() ) : the_post();
                    $template_format = get_the_terms( get_the_ID(), EASL_Event_Config::get_format_slug() );
                    if ( $template_format ) {
                        $template_format = $template_format[0]->slug;
                    } else {
                        $template_format = '';
                    }
                    // Single Event
                    get_template_part( 'partials/event/base-layout', $template_format );
					

				endwhile; ?>

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->
        <?php get_template_part('partials/event/recommended') ?>

		<?php //wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>