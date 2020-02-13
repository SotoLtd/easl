<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$sd_color = easl_get_slide_deck_topic_color( get_the_ID() );


$has_landing_page    = get_field( 'sd_has_landing_page' );
$landing_page_link   = get_field( 'sd_landing_page_url' );
$download_link       = get_field( 'slide-decks-file' );
$landing_page_url    = '';
$landing_page_title  = '';
$landing_page_target = '';
if ( $landing_page_link ) {
	$landing_page_url    = $landing_page_link['url'];
	$landing_page_title  = $landing_page_link['title'] ? $landing_page_link['title'] : __( 'Download', 'total-child' );
	$landing_page_target = $landing_page_link['target'] ? $landing_page_link['target'] : '_self';
}

$title = get_the_title();
if ( $has_landing_page ) {
	if ( $landing_page_url ) {
		$title = '<a href="' . esc_url( $landing_page_url ) . '" target="' . $landing_page_target . '">' . get_the_title() . '</a>';
	} else {
		$title = get_the_title();
	}
} elseif ( $download_link ) {
	$title = '<a href="' . esc_url( $download_link ) . '" target="_blank" download="' . basename( parse_url( $download_link, PHP_URL_PATH ) ) . '">' . get_the_title() . '</a>';
}
?>
<li class="easl-color-<?php echo $sd_color; ?>">
	<?php echo $title; ?>
</li>