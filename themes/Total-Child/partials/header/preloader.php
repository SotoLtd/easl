<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$preloaer_eanbled = wpex_get_mod( 'easl_enable_preloader' );
?>
<?php if ( $preloaer_eanbled ): ?>
    <div class="easl-preloader">
        <div class="easl-loading-image-con"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/easl-loader.gif" alt="<?php _e( 'Loading...', 'total-child' ); ?>"/></div>
    </div>
<?php endif; ?>