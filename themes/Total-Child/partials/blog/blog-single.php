<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Return if disabled
if ( ! wpex_get_mod( 'blog_post_date_source', true ) ) {
	return;
}
?>
<p class="easl-news-list-meta">
	<span class="easl-news-list-date"></span><?php echo wpex_date_format( array(
		'id'     => get_the_ID(),
		'format' => 'd/m/y',
	) ); ?></span>
	<?php
	$sources = get_the_term_list( get_the_ID(), EASL_News_Source_Tax::get_slug(), '', ',', '' );
	if ( $sources ):
		?>
		- <span class="easl-news-list-source"><?php echo $sources; ?></span>
	<?php endif; ?>
</p>
