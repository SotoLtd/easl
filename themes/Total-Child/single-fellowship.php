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

get_header(); ?>

    <div id="content-wrap" class="container clr">

        <?php wpex_hook_primary_before(); ?>

        <div id="primary" class="content-area clr" style="margin-top: 48px; padding-bottom: 0px;">

            <?php wpex_hook_content_before(); ?>

            <div id="content" class="site-content clr">

                <?php wpex_hook_content_top(); ?>

                <?php
                // Start loop
                while ( have_posts() ) : the_post();

                    // Single Event

                    get_template_part('partials/fellowship/fellowship-single-layout');


                endwhile; ?>

                <?php wpex_hook_content_bottom(); ?>

            </div><!-- #content -->

            <?php wpex_hook_content_after(); ?>

        </div><!-- #primary -->

        <?php wpex_hook_sidebar_before(); ?>

        <aside id="sidebar" class="sidebar-container sidebar-primary"<?php wpex_schema_markup( 'sidebar' ); ?><?php wpex_aria_landmark( 'sidebar' ); ?>>

            <?php wpex_hook_sidebar_top(); ?>

            <div id="sidebar-inner" class="clr">

                <?php dynamic_sidebar('fellowship-detail-sidebar ');?>

            </div><!-- #sidebar-inner -->

            <?php wpex_hook_sidebar_bottom(); ?>

        </aside><!-- #sidebar -->

        <?php wpex_hook_sidebar_after(); ?>



    </div><!-- .container -->

<?php get_footer(); ?>