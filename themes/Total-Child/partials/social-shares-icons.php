<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$newsletter             = get_field( 'sign_up_to_easl_news_link', 'options' );
$newsletter_link        = '';
$newsletter_link_title  = '';
$newsletter_link_target = '';
if ( ! empty( $newsletter['url'] ) ) {
    $newsletter_link = esc_url( $newsletter['url'] );
}
if ( ! empty( $newsletter['title'] ) ) {
    $newsletter_link_title = $newsletter['title'];
} else {
    $newsletter_link_title = __( 'Sign up to EASL News', 'total-child' );
}
if ( ! empty( $newsletter['target'] ) ) {
    $newsletter_link_target = 'target="_blank"';
}
?>
<div class="easl-content-share-row">
	<div class="easl-sss-share-wrap">
		<?php echo do_shortcode('[Sassy_Social_Share title="Share"]'); ?>
	</div>
    <?php if ($newsletter_link): ?>
	<div class="easl-newsletter-wrap">
        <div class="easl-newsletter-inner">
		    <a href="<?php echo $newsletter_link; ?>" <?php echo $newsletter_link_target; ?> class="sign-up-news easl-generic-button easl-color-lightblue"><?php echo $newsletter_link_title; ?> <span class="easl-generic-button-icon"><span class="ticon ticon-chevron-right"></span></span></a>
        </div>
	</div>
    <?php endif; ?>
</div>
