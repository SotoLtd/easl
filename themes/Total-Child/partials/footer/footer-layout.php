<?php
/**
 * Footer Layout
 *
 * @package Total WordPress Theme
 * @subpackage Partials
 * @version 4.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} ?>

<?php wpex_hook_footer_before(); ?>

<?php if ( wpex_footer_has_widgets() ) : ?>

    <footer id="footer" class="site-footer"<?php wpex_schema_markup( 'footer' ); ?>>

        <?php wpex_hook_footer_top(); ?>

        <div id="footer-inner" class="site-footer-inner container clr">
			<div class="footer-hepatocyte-logo">
				<img alt="EASL Hepatocyte Logo" src="<?php echo get_stylesheet_directory_uri(); ?>/images/hepatocyte-logo.png" width="65" height="63"/>
			</div>
			<div class="footer-inner-container">
				<?php wpex_hook_footer_inner(); // widgets are added via this hook ?> 

				<?php wpex_hook_footer_bottom(); ?>
				
			</div>	
        </div><!-- #footer-widgets -->
    </footer><!-- #footer -->

<?php endif; ?> 
<?php if ( wpex_get_mod( 'footer_bottom_newsletter', true ) ) : ?>
	<div class="footer-newsletter">
        <a href="#" class="show-footer-newsletter easl-hide-desktop footer-nl-close-icon"><i class="ticon ticon-times-circle"></i></a>
		<div class="footer-newsletter-inner container clr">
			<?php echo do_shortcode( wpex_get_mod( 'footer_bottom_newsletter_sc' ) ); ?>
            <a href="#" class="show-footer-newsletter easl-hide-mobile footer-nl-close-button"><?php _e('Cancel', 'total-child'); ?></a>
		</div>
	</div>
<?php endif; ?>
<?php wpex_hook_footer_after(); ?>