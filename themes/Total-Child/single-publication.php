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

		<div id="primary" class="clr">

			<?php wpex_hook_content_before(); ?>

			<div id="content" class="site-content clr">

				<?php wpex_hook_content_top(); ?>

				<?php
				// Start loop
				while ( have_posts() ) : the_post();
				    $members_only = get_field('enable_members_only', get_the_ID());
				    if(is_null($members_only)) {
				        $members_only = true;
                    }
                    if ( has_term('policy-statements', 'publication_category', get_the_ID()) || easl_mz_user_can_access_memberzone_page( get_the_ID() ) ) {
                        // Single publication
                        get_template_part( 'partials/publication/publication-single-layout' );
                    } elseif ( easl_mz_is_member_logged_in() ) {
                        echo '<script>window.location.href='. esc_url(get_field('membership_plan_url', 'option')) .'</script>';
                    }else{
                        get_template_part('partials/publication/publication-modal');
                    }

				endwhile; ?>

				<?php wpex_hook_content_bottom(); ?>

			</div><!-- #content -->

			<?php wpex_hook_content_after(); ?>

		</div><!-- #primary -->

		<?php //wpex_hook_primary_after(); ?>

	</div><!-- .container -->

<?php get_footer(); ?>