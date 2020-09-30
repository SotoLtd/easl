<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$event_poster_image       = get_field( 'se_poster_image' );
$poster_download_link     = get_field( 'se_poster_download_link' );
$event_poster_text_source = get_field( 'se_poster_text_source' );
$event_poster_custom_text = get_field( 'se_poster_custom_text' );
if ( ! in_array( $event_poster_text_source, array( 'default', 'no', 'custom' ) ) ) {
	$event_poster_text_source = 'default';
}
$event_poster_text = '';
if ( $event_poster_text_source == 'no' ) {
	$event_poster_text = '';
} elseif ( $event_poster_text_source == 'custom' ) {
	$event_poster_text = trim( $event_poster_custom_text );
} else {
	$event_poster_text = trim( wpex_get_mod( 'event_poster_text' ) );
}

if ( $event_poster_image || $poster_download_link ):
	?>
    <div class="easl-small-event-sbitem easl-small-event-sbitem-countdown">
        <div class="easl-small-event-sbitem-inner">
            <div class="event-poster-image-box event-image-box-bg">
				<?php if ( $event_poster_image ): ?>
                    <div class="eib-image">
                        <img alt="" src="<?php echo $event_poster_image; ?>"/>
                    </div>
				<?php endif; ?>
				<?php if ( $event_poster_text ): ?>
                    <p><?php echo wp_kses( $event_poster_text, array(
							'br'   => array(),
							'span' => array(
								'style' => array(),
								'class' => array(),
							)
						) ); ?></p>
				<?php endif; ?>
				<?php if ( $poster_download_link && ! empty( $poster_download_link['url'] ) ): ?>
                    <a class="event-button event-button-icon event-button-light-blue event-button-icon-download event-image-box-full-button" href="<?php echo esc_url( $poster_download_link['url'] ); ?>" <?php if ( $poster_download_link['target'] ) {
						echo 'target="' . esc_attr( $poster_download_link['target'] ) . '"';
					} ?> download>
						<?php if ( ! empty( $poster_download_link['title'] ) ) {
							echo esc_html( $poster_download_link['title'] );
						} else {
							_e( 'Download Poster', 'total-child' );
						} ?>
                    </a>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
