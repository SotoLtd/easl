<?php
/**
 * Template Name: Member Zone Pages
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

    <div id="content-wrap" class="easl-member-zone-container container clr">

		<?php wpex_hook_primary_before(); ?>

        <div id="primary" class="content-area clr">

			<?php wpex_hook_content_before(); ?>

            <div id="content" class="site-content clr">

				<?php wpex_hook_content_top(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

                    <article id="single-blocks" class="single-page-article wpex-clr">
                        <div class="single-content single-page-content entry clr">
                            <?php do_action('easl_mz_memberzone_page_content'); ?>
                        </div>
                    </article>

				<?php endwhile; ?>

				<?php wpex_hook_content_bottom(); ?>

            </div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

        </div><!-- #primary -->

		<?php wpex_hook_primary_after(); ?>

    </div><!-- .container -->

<?php get_footer(); ?>