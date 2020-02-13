<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$content = wpex_header_aside_content();
$content = trim($content);
if($content):
?>
<div class="easl-mobile-top-buttons easl-hide-desktop">
	<div class="container clr">
		<?php echo do_shortcode( $content ); ?>
	</div>
</div>
<?php endif; ?>
